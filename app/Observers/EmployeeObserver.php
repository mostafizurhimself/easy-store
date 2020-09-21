<?php

namespace App\Observers;

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
    public function saving(Employee $employee)
    {
        $designation = Designation::find($employee->designation_id);
        $employee->department_id = $designation->department ? $designation->department->id : null ;
        $employee->section_id = $designation->section ? $designation->section->id : null ;
        $employee->sub_section_id = $designation->subSection ? $employee->designation->subSection->id : null ;
    }

    /**
     * Handle the employee "saved" event.
     *
     * @param  \App\Models\Employee  $employee
     * @return void
     */
    public function saved(Employee $employee)
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
