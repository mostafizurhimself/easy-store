<?php

namespace App\Observers;

use App\Models\Employee;

class EmployeeObserver
{
    /**
     * Handle the employee "created" event.
     *
     * @param  \App\Models\Employee  $employee
     * @return void
     */
    public function saving(Employee $employee)
    {
        $employee->department_id = $employee->designation->department ? $employee->designation->department->id : null ;
        $employee->section_id = $employee->designation->section ? $employee->designation->section->id : null ;
    }

    /**
     * Handle the employee "updated" event.
     *
     * @param  \App\Models\Employee  $employee
     * @return void
     */
    public function updating(Employee $employee)
    {
        //
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
