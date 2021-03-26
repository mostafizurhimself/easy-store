<?php

namespace App\Observers;

use DateTime;
use Exception;
use Carbon\Carbon;
use App\Models\Shift;
use App\Facades\Timesheet;
use App\Models\Attendance;
use App\Enums\AttendanceStatus;
use Illuminate\Validation\ValidationException;

class AttendanceObserver
{
    /**
     * Handle the Attendance "saving" event.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function creating(Attendance $attendance)
    {
        $in = Carbon::createFromTimeString($attendance->in);

        // Check if this attendance is exists
        $checkAttendance = Attendance::where('location_id', $attendance->locationId)
            ->where('date', $attendance->date)
            ->where('employee_id', $attendance->employeeId)
            ->first();

        if ($checkAttendance) {
            $attendance = $checkAttendance;
            throw new Exception('Attendance already exists.');
            // throw ValidationException::withMessages(['employee_id' => 'Attendance already exists for this employee']);
        }

        // Set the opening hour
        $openingHours = Timesheet::getOpeningHours($attendance->locationId, $attendance->shiftId);

        // Set the working hour
        $workingHour = Timesheet::getWorkingHours($attendance->shiftId, $attendance->date);

        // Set the opening hour
        $attendance->openingHour = json_encode(Timesheet::getWorkingRange($attendance->shiftId, $attendance->date));

        // Set total late in seconds
        $attendance->late = Timesheet::getLate($attendance->shiftId, $attendance->date, $attendance->in) ?? null;

        // Set total early leave in seconds
        if ($attendance->out) {
            $out = Carbon::createFromTimeString($attendance->out);

            // Set total work in seconds
            $attendance->totalWork = $out->diffInSeconds($in);
            // Set total earyly leave in seconds
            $attendance->earlyLeave = Timesheet::getEarlyLeave($attendance->shiftId, $attendance->date, $attendance->out) ?? null;

            // Check is open or not
            if ($openingHours->isOpenOn($attendance->date->format('Y-m-d'))) {

                // Set the overtime
                if ($attendance->totalWork > $workingHour) {
                    $attendance->overtime = $attendance->totalWork - $workingHour;
                }
            } else {
                // Set total work as overtime
                // if is not open
                $attendance->overtime = $attendance->totalWork;
            }
        }
    }

    /**
     * Handle the Attendance "updating" event.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function updating(Attendance $attendance)
    {
        $in = Carbon::createFromTimeString($attendance->in);

        // Set the opening hour
        $openingHours = Timesheet::getOpeningHours($attendance->locationId, $attendance->shiftId);

        // Set the working hour
        $workingHour = Timesheet::getWorkingHours($attendance->shiftId, $attendance->date);

        // Set the opening hour
        $attendance->openingHour = json_encode(Timesheet::getWorkingRange($attendance->shiftId, $attendance->date));

        // Set total late in seconds
        $attendance->late = Timesheet::getLate($attendance->shiftId, $attendance->date, $attendance->in) ?? null;

        // Set total early leave in seconds
        if ($attendance->out) {
            $out = Carbon::createFromTimeString($attendance->out);

            // Set total work in seconds
            $attendance->totalWork = $out->diffInSeconds($in);
            // Set total earyly leave in seconds
            $attendance->earlyLeave = Timesheet::getEarlyLeave($attendance->shiftId, $attendance->date, $attendance->out) ?? null;

            // Check is open or not
            if ($openingHours->isOpenOn($attendance->date->format('Y-m-d'))) {

                // Set the overtime
                if ($attendance->totalWork > $workingHour) {
                    $attendance->overtime = $attendance->totalWork - $workingHour;
                }
            } else {
                // Set total work as overtime
                // if is not open
                $attendance->overtime = $attendance->totalWork;
            }
        }
    }

    /**
     * Handle the Attendance "deleted" event.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function deleted(Attendance $attendance)
    {
        //
    }

    /**
     * Handle the Attendance "restored" event.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function restored(Attendance $attendance)
    {
        //
    }

    /**
     * Handle the Attendance "force deleted" event.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function forceDeleted(Attendance $attendance)
    {
        //
    }
}
