<?php

namespace App\Nova\Lenses\Employee;

use Carbon\Carbon;
use App\Facades\Timesheet;
use App\Models\Attendance;
use App\Models\Department;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\EmployeeStatus;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\Badge;
use Illuminate\Support\Facades\DB;
use App\Nova\Filters\DepartmentFilter;
use AwesomeNova\Filters\DependentFilter;
use Laravel\Nova\Http\Requests\LensRequest;
use App\Nova\Filters\Lens\EmployeeLocationFilter;
use App\Nova\Filters\Lens\EmployeeHistoryDateRangeFilter;
use App\Nova\Actions\Employees\EmployeeHistory\DownloadPdf;
use App\Nova\Actions\Employees\EmployeeHistory\DownloadExcel;

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
        if ($request->user()->locationId && !$request->user()->hasPermissionTo('view any locations data')) {
            $query->where('employees.location_id', $request->user()->location_id)->whereNull('resign_date');
        } else {
            $query->whereNull('resign_date');
        }
        return $request->withoutTableOrderPrefix()->withOrdering($request->withFilters(
            $query->select(self::columns())
                ->leftJoin('locations', 'employees.location_id', '=', 'locations.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
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
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('Employee Id', 'readable_id')
                ->sortable(),

            Text::make('Name', 'name')
                ->sortable(),

            Text::make('Location', 'location_name')
                ->sortable(),

            Text::make('Department', 'employee_department')
                ->sortable(),

            Text::make('Working Days', function () {
                $workingDays = Timesheet::getWorkingDays($this->location_id, $this->shift_id, $this->start_date, $this->end_date);

                return $workingDays . " days";
            }),

            Text::make('Present', function () {
                return $this->total_present . " days";
            }),

            Text::make('Leave', function () {
                return $this->total_leave . " days";
            }),

            Text::make('Absent', function () {
                $workingDays = Timesheet::getWorkingDays($this->location_id, $this->shift_id, $this->start_date, $this->end_date);

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

            Badge::make('Status', 'status')->map([
                EmployeeStatus::ACTIVE()->getValue()   => 'success',
                EmployeeStatus::VACATION()->getValue() => 'warning',
                EmployeeStatus::INACTIVE()->getValue() => 'danger',
                EmployeeStatus::RESIGNED()->getValue() => 'danger',
            ])
                ->sortable()
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            Text::make(__('Date Range'), function () {
                return "{$this->start_date} - {$this->end_date}";
            })
                ->onlyOnExport(),
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
        return [
            EmployeeLocationFilter::make('Location', 'location_id')->canSee(function ($request) {
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
            }),

            DependentFilter::make('Department', 'department_id')
                ->dependentOf('location_id')
                ->withOptions(function (Request $request, $filters) {
                    return Department::where('location_id', $filters['location_id'])
                        ->orderBy('name')
                        ->pluck('name', 'id');
                })->canSee(function ($request) {
                    return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
                }),

            (new DepartmentFilter)->canSee(function ($request) {
                return !($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data'));
            }),

            new EmployeeHistoryDateRangeFilter(),
        ];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new DownloadPdf)->withHeadings('#', 'Employee Id', 'Name', 'Location', 'Department', 'Working Days', 'Present', 'Leave', 'Absent', 'Early Leave', 'Gate Pass', 'Outside Spent', 'Status', 'Date Range')
                ->canSee(function ($request) {
                    return ($request->user()->hasPermissionTo('can download employees') || $request->user()->isSuperAdmin());
                })
                ->canRun(function ($request) {
                    return ($request->user()->hasPermissionTo('can download employees') || $request->user()->isSuperAdmin());
                })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download excel?")
                ->withWriterType(\Maatwebsite\Excel\Excel::MPDF)
                ->withFilename('employee_history.pdf'),

            (new DownloadExcel)->withHeadings('#', 'Employee Id', 'Name', 'Location', 'Department', 'Working Days', 'Present', 'Leave', 'Absent', 'Early Leave', 'Gate Pass', 'Outside Spent', 'Status', 'Date Range')
                ->canSee(function ($request) {
                    return ($request->user()->hasPermissionTo('can download employees') || $request->user()->isSuperAdmin());
                })
                ->canRun(function ($request) {
                    return ($request->user()->hasPermissionTo('can download employees') || $request->user()->isSuperAdmin());
                })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download excel?")
                ->withFilename('employee_history.xlsx'),
        ];
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