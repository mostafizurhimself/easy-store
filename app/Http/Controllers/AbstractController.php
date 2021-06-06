<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shift;
use App\Facades\Helper;
use App\Models\Holiday;
use App\Models\License;
use App\Models\Employee;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\OpeningHours\OpeningHours;

class AbstractController extends Controller
{
    /**
     * Create a new license
     */
    public function createLicense(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'transaction_id' => 'required|string|between:2,100',
            'invoice_no'    => 'nullable|string|between:2,100',
            'amount' => 'required|numeric|min:5000',
        ]);

        $license = License::first();
        $license->package = $request->get('package');
        $license->transaction_id = $request->get('transaction_id');
        $license->invoice_no = $request->get('invoice_no');
        $license->amount = $request->get('amount');
        $license->expiration_date = $license->expiration_date->addDays(30);
        $license->status = 'inactive';
        $license->save();

        return redirect()->back()->with('license', $license);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shift         $shift
     * @return \Illuminate\Http\Response
     */
    public function generateSchedule(Request $request, Shift $shift)
    {
        $holidays = Holiday::orderBy('start', 'desc')->get();

        $exceptions = [];

        // Merge the holidays
        foreach ($holidays as $holiday) {
            $days = Helper::getAllDates($holiday->start, $holiday->end);

            foreach ($days as $day) {
                $exceptions[$day] = [
                    'hours' => [],
                    'data'  => $holiday->name
                ];
            }
        }

        // Creating an opening hour object
        $openingHours = OpeningHours::create(array_merge($shift->openingHours, ['exceptions' => $exceptions]));

        // dd($openingHours);

        // Get the dates form the range
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);
        $dates = Helper::getAllDates($start, $end);

        return view('others.schedule', compact('dates', 'openingHours'));
    }

    /**
     * Generate attendance report for day
     *
     * @return \Illuminate\Http\Response
     */
    public function attendanceReport($date, $location)
    {
        $date = Carbon::parse($date)->format('Y-m-d');
        $location = Location::find($location);
        $result = DB::table('attendances')
            ->where('date', $date)
            ->where('attendances.location_id', $location->id)
            ->select('departments.name as department', DB::raw("count(attendances.employee_id) as total"))
            ->join('employees', 'attendances.employee_id', 'employees.id')
            ->join('departments', 'employees.department_id', 'departments.id')
            ->groupBy('departments.name')
            ->get();

        $totalEmployee = Employee::where('location_id', $location->id)->count();
        $totalPresent  = $result->sum('total');
        $totalAbsent   = $totalEmployee - $totalPresent;


        // dd($result);

        if(empty($result)){
            return "No data found";
        }
        return view('others.attendance-report', compact('result', 'totalEmployee', 'totalAbsent', 'totalPresent', 'date', 'location'));



    }
}
