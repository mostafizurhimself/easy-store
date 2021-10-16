<?php

namespace Easystore\ScanAttendance\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Rules\AttendanceRule;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Take attendance of given employee
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'type'       => ['required'],
            'employeeId' => ['required']
        ]);

        $employee = Employee::find($request->employeeId);


        if ($employee) {
            // Find the attendance
            $attendance = Attendance::where('employee_id', $request->employeeId)->where('date', Carbon::now()->format('Y-m-d'))->first();

            // If attendance type is check in
            if ($request->type == "1") {
                // Check if attendance exists;
                if ($attendance) {
                    return response()->json(['message' => 'Attendance already taken'], 400);
                }
                Attendance::create([
                    'locationId' => $employee->locationId,
                    'employeeId' => $employee->id,
                    'shiftId'    => $employee->shiftId,
                    'date'       => Carbon::now()->format('Y-m-d'),
                    'in'         => Carbon::now()->format('H:i:s'),
                ]);
                return response(['message' => 'Checked in successfully']);
            } else {
                if (empty($attendance)) {
                    return response()->json(['message' => 'Please take attendance first'], 400);
                } else {
                    $attendance->update(['out' => Carbon::now()->format('H:i:s')]);
                    return response(['message' => 'Checked out successfully']);
                }
            }
        }

        return response()->json(['message' => 'Employee not found'], 404);
    }
}