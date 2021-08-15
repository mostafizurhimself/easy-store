<?php

namespace App\Observers;

use App\Facades\Helper;
use App\Models\Leave;

class LeaveObserver
{
    /**
     * Handle the Leave "created" event.
     *
     * @param  \App\Models\Leave  $leave
     * @return void
     */
    public function created(Leave $leave)
    {
        $dates = Helper::getAllDates($leave->from, $leave->to);

        // Insert leave days
        foreach ($dates as $date) {
            $leave->leaveDays()->create([
                'date'        => $date,
                'employee_id' => $leave->employeeId,
            ]);
        }
    }

    /**
     * Handle the Leave "updated" event.
     *
     * @param  \App\Models\Leave  $leave
     * @return void
     */
    public function updated(Leave $leave)
    {
        if ($leave->isDirty('from') || $leave->isDirty('to')) {
            // Remove previous leave days
            $leave->leaveDays()->delete();

            // Get days
            $dates = Helper::getAllDates($leave->from, $leave->to);

            // Insert leave days
            foreach ($dates as $date) {
                $leave->leaveDays()->create([
                    'date'        => $date,
                    'employee_id' => $leave->employeeId
                ]);
            }
        }
    }

    /**
     * Handle the Leave "deleted" event.
     *
     * @param  \App\Models\Leave  $leave
     * @return void
     */
    public function deleted(Leave $leave)
    {
        //
    }

    /**
     * Handle the Leave "restored" event.
     *
     * @param  \App\Models\Leave  $leave
     * @return void
     */
    public function restored(Leave $leave)
    {
        //
    }

    /**
     * Handle the Leave "force deleted" event.
     *
     * @param  \App\Models\Leave  $leave
     * @return void
     */
    public function forceDeleted(Leave $leave)
    {
        //
    }
}