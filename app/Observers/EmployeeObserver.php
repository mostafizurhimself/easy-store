<?php

namespace App\Observers;

use App\Enums\EmployeeStatus;
use Exception;
use App\Facades\Helper;
use App\Models\Employee;
use App\Models\Designation;

class EmployeeObserver
{
    /**
     * Handle the employee "created" event.
     *
     * @param  \App\Models\Employee  $employee
     * @return void
     */
    public function created(Employee $employee)
    {
        if (empty($employee->designation->code)) {
            throw new Exception("Designation code in not defined.");
        }
        $employee->readableId = Helper::generateEmployeeId($employee->id, $employee->designation->code);
        $employee->save();
    }

    /**
     * Handle the employee "updating" event.
     *
     * @param  \App\Models\Employee  $employee
     * @return void
     */
    public function updating(Employee $employee)
    {
        if ($employee->resignDate) {
            $employee->status = EmployeeStatus::RESIGNED();
        } else {
            $employee->status = EmployeeStatus::ACTIVE();
        }

        if (empty($employee->designation->code)) {
            throw new Exception("Designation code in not defined.");
        }
        $employee->readableId = Helper::generateEmployeeId($employee->id, $employee->designation->code);
    }

    /**
     * Handle the employee "deleted" event.
     *
     * @param  \App\Models\Employee  $employee
     * @return void
     */
    public function deleted(Employee $employee)
    {
        //
    }

    /**
     * Handle the employee "restored" event.
     *
     * @param  \App\Models\Employee  $employee
     * @return void
     */
    public function restored(Employee $employee)
    {
        //
    }

    /**
     * Handle the employee "force deleted" event.
     *
     * @param  \App\Models\Employee  $employee
     * @return void
     */
    public function forceDeleted(Employee $employee)
    {
        //
    }
}