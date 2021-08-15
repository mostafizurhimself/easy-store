<?php

namespace App\Observers;

use App\Models\EmployeeGatePass;
use Carbon\Carbon;

class EmployeeGatePassObserver
{
    /**
     * Handle the EmployeeGatePass "saving" event.
     *
     * @param  \App\Models\EmployeeGatePass  $employeeGatePass
     * @return void
     */
    public function saving(EmployeeGatePass $employeeGatePass)
    {
        if ($employeeGatePass->in) {
            $employeeGatePass->earlyLeave = false;
            $employeeGatePass->spent = Carbon::parse($employeeGatePass->in)->diffInSeconds($employeeGatePass->out);
        }
    }

    /**
     * Handle the EmployeeGatePass "updated" event.
     *
     * @param  \App\Models\EmployeeGatePass  $employeeGatePass
     * @return void
     */
    public function updated(EmployeeGatePass $employeeGatePass)
    {
        //
    }

    /**
     * Handle the EmployeeGatePass "deleted" event.
     *
     * @param  \App\Models\EmployeeGatePass  $employeeGatePass
     * @return void
     */
    public function deleted(EmployeeGatePass $employeeGatePass)
    {
        //
    }

    /**
     * Handle the EmployeeGatePass "restored" event.
     *
     * @param  \App\Models\EmployeeGatePass  $employeeGatePass
     * @return void
     */
    public function restored(EmployeeGatePass $employeeGatePass)
    {
        //
    }

    /**
     * Handle the EmployeeGatePass "force deleted" event.
     *
     * @param  \App\Models\EmployeeGatePass  $employeeGatePass
     * @return void
     */
    public function forceDeleted(EmployeeGatePass $employeeGatePass)
    {
        //
    }
}