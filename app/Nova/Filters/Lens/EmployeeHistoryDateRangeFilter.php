<?php

namespace App\Nova\Filters\Lens;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Illuminate\Support\Facades\DB;
use Ampeco\Filters\DateRangeFilter;

class EmployeeHistoryDateRangeFilter extends DateRangeFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = "Date Between";

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $from = Carbon::parse($value[0])->startOfDay();
        $to = Carbon::parse($value[1])->endOfDay();

        return $query->select(self::columns($from, $to));
    }

    /**
     * Get the columns that should be selected.
     *
     * @return array
     */
    protected static function columns($start, $end)
    {
        return [
            'employees.id',
            'employees.first_name',
            'employees.last_name',
            'employees.readable_id',
            'departments.name as employee_department',
            'employees.location_id as location_id',
            'employees.shift_id as shift_id',
            'employees.status as status',
            DB::raw("'$start' as start_date"),
            DB::raw("'$end' as end_date"),

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
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [];
    }
}