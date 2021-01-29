<?php

namespace App\Observers;

use DateTime;
use Carbon\Carbon;
use App\Models\Shift;
use App\Facades\Timesheet;
use App\Models\Attendance;

class AttendanceObserver
{
    /**
     * Handle the Attendance "saving" event.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function saving(Attendance $attendance)
    {
        $in = Carbon::createFromTimeString($attendance->in);
        $out = Carbon::createFromTimeString($attendance->out);
        $attendance->totalWork = $out->diffInSeconds($in);

        // Get the opening hour
        $openingHours = Timesheet::getOpeningHours($attendance->locationId, $attendance->shiftId);

        // Get the working hour
        $workingHour = Timesheet::getWorkingHours($attendance->shiftId, $attendance->date);

        // Get total late in seconds
        $attendance->late = Timesheet::getLate($attendance->shiftId, $attendance->date, $attendance->in) ?? null;

        // Get total early leave in seconds
        $attendance->earlyLeave = Timesheet::getEarlyLeave($attendance->shiftId, $attendance->date, $attendance->out) ?? null;

        // Check is open or not
        if($openingHours->isOpenOn($attendance->date->format('Y-m-d'))){

            // Set the overtime
            if($attendance->totalWork > $workingHour){
                $attendance->overtime = $attendance->totalWork - $workingHour;
            }

        }else{
            // Set total work as overtime
            // if is not open
            $attendance->overtime = $attendance->totalWork;
        }

    }

    /**
     * Handle the Attendance "updated" event.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function updated(Attendance $attendance)
    {
        //
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
