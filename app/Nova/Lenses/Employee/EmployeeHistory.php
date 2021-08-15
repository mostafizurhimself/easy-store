<?php

namespace App\Nova\Lenses\Employee;

use App\Facades\Timesheet;
use App\Models\Attendance;
use Carbon\Carbon;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\LensRequest;

class EmployeeHistory extends Lens
{
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withoutTableOrderPrefix()->withOrdering($request->withFilters(
            $query->select(self::columns())
                ->leftJoin('locations', 'employees.location_id', '=', 'locations.id')
                ->where('employees.deleted_at', "=", null)
                ->groupBy('employees.id')
                ->orderBy('employees.id', 'desc')
                ->withoutGlobalScopes()
        ));
    }

    /**
     * Get the columns that should be selected.
     *
     * @return array
     */
    protected static function columns()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end   = Carbon::now()->format('Y-m-d');
        return [
            'employees.id',
            'employees.first_name',
            'employees.last_name',
            'employees.readable_id',
            'employees.location_id as location_id',
            'employees.shift_id as shift_id',
            'employees.joining_date as joining_date',

            'locations.name as location_name',

            DB::raw("CONCAT(employees.first_name,' ',employees.last_name) AS name"),

            // Present
            DB::raw("(COALESCE((select count(attendances.id) from attendances
            where attendances.date between '$start' and '$end'
            and attendances.employee_id = employees.id
            and attendances.deleted_at is null
            and attendances.status = 'confirmed'), 0)) as total_present"),

            // Leave
            DB::raw("(COALESCE((select count(leave_days.id) from leave_days
            left join leaves on leave_days.leave_id = leaves.id
            where leave_days.date between '$start' and '$end'
            and leave_days.employee_id = employees.id
            and leave_days.deleted_at is null
            and leaves.status = 'approved'), 0)) as total_leave"),

            // Late
            DB::raw("(COALESCE((select count(attendances.id) from attendances
            where attendances.date between '$start' and '$end'
            and attendances.late > 0
            and attendances.employee_id = employees.id
            and attendances.deleted_at is null
            and attendances.status = 'confirmed'), 0)) as total_late"),

            // Early Leave
            DB::raw("(COALESCE((select count(employee_gate_passes.id) from employee_gate_passes
            where date(employee_gate_passes.passed_at) between '$start' and '$end'
            and employee_gate_passes.early_leave = 1
            and employee_gate_passes.employee_id = employees.id
            and employee_gate_passes.deleted_at is null
            and employee_gate_passes.status = 'passed'), 0)) as total_early_leave"),

            // Gate pass
            DB::raw("(COALESCE((select count(employee_gate_passes.id) from employee_gate_passes
            where date(employee_gate_passes.passed_at) between '$start' and '$end'
            and employee_gate_passes.employee_id = employees.id
            and employee_gate_passes.deleted_at is null
            and employee_gate_passes.status = 'passed'), 0)) as total_gate_pass"),

            // Outside spent
            DB::raw("(COALESCE((select sum(employee_gate_passes.spent) from employee_gate_passes
            where date(employee_gate_passes.passed_at) between '$start' and '$end'
            and employee_gate_passes.employee_id = employees.id
            and employee_gate_passes.in is not null
            and employee_gate_passes.deleted_at is null
            and employee_gate_passes.status = 'passed'), 0)) as total_outside_spent"),
        ];
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {

        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('Location', 'location_name')
                ->sortable(),

            Text::make('Employee Id', 'readable_id')
                ->sortable(),

            Text::make('Name', 'name')
                ->sortable(),

            Text::make('Working Days', function () {
                $workingDays = Timesheet::getWorkingDays($this->location_id, $this->shift_id, Carbon::now()->startOfMonth()->format("Y-m-d"), Carbon::now()->format('Y-m-d'));

                return $workingDays . " days";
            }),

            Text::make('Present', function () {
                return $this->total_present . " days";
            }),

            Text::make('Leave', function () {
                return $this->total_leave . " days";
            }),

            Text::make('Absent', function () {
                $workingDays = Timesheet::getWorkingDays($this->location_id, $this->shift_id, Carbon::now()->startOfMonth()->format("Y-m-d"), Carbon::now()->format('Y-m-d'));

                return ($workingDays - $this->total_present - $this->total_leave) . " days";
            }),

            Text::make('Early Leave', function () {
                return $this->total_late . " days";
            }),

            Text::make('Gate Pass', function () {
                return $this->total_gate_pass;
            }),

            Text::make('Outside Spent', function () {
                return gmdate("H:i", $this->total_outside_spent) . " hrs";
            }),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'employee-history';
    }
}