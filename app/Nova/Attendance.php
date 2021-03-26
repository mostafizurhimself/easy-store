<?php

namespace App\Nova;

use Carbon\Carbon;
use Michielfb\Time\Time;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use App\Enums\ConfirmStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use App\Enums\AttendanceStatus;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\DateRangeFilter;
use Laraning\NovaTimeField\TimeField;
use App\Nova\Actions\Attendances\Confirm;
use App\Nova\Actions\Attendances\CheckOut;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\Attendances\BulkAttendance;
use App\Nova\Actions\Attendances\BulkAttendanceAdmin;
use App\Nova\Filters\AttendanceStatusFilter;
use App\Nova\Lenses\Attendance\DailyAttendance;

class Attendance extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Attendance::class;

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Time Section';

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 4;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can take bulk', 'can confirm', 'can download'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Indicates if the resource should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = false;

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-calendar-check';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Date::make('Date')
                ->default(Carbon::now())
                ->sortable(),

            BelongsTo::make('Location')
                ->searchable()
                ->sortable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Employee', 'employee', \App\Nova\Employee::class)
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Employee', 'employee', \App\Nova\Employee::class)
                ->sortable()
                ->searchable()
                ->canSee(function ($request) {
                    if (!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            AjaxSelect::make('Employee', 'employee_id')
                ->rules('required')
                ->get('/locations/{location}/employees')
                ->parent('location')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Shift', 'shift', \App\Nova\Shift::class)
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Shift', 'shift', \App\Nova\Shift::class)
                ->sortable()
                ->searchable()
                ->canSee(function ($request) {
                    if (!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            AjaxSelect::make('Shift', 'shift_id')
                ->rules('required')
                ->get('/locations/{location}/shifts')
                ->parent('location')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Time::make('In', 'in')
                ->sortable()
                ->format('HH:mm')
                ->required(),

            Time::make('Out', 'out')
                ->format('HH:mm')
                ->sortable()
                ->exceptOnForms(),

            Text::make('Opening Hour', function () {
                $range = json_decode($this->openingHour);
                return "{$range[0]} to {$range[1]}";
            })
                ->onlyOnDetail(),

            Text::make('Late', 'late')
                ->sortable()
                ->displayUsing(function ($late) {
                    return gmdate("H:i:s", $late);
                })
                ->exceptOnForms(),

            Text::make('Total Work')
                ->sortable()
                ->displayUsing(function ($totalWork) {
                    return gmdate("H:i:s", $totalWork);
                })
                ->onlyOnDetail(),

            Text::make('Early Leave')
                ->sortable()
                ->displayUsing(function ($earlyLeave) {
                    return gmdate("H:i:s", $earlyLeave);
                })
                ->onlyOnDetail(),

            Text::make('Overtime')
                ->sortable()
                ->displayUsing(function ($overtime) {
                    return gmdate("H:i:s", $overtime);
                })
                ->onlyOnDetail(),

            Badge::make('Status')->map([
                ConfirmStatus::DRAFT()->getValue()       => 'warning',
                ConfirmStatus::CONFIRMED()->getValue()   => 'info',
            ])
                ->sortable()
                ->onlyOnDetail()
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new DateRangeFilter('date'),

            new AttendanceStatusFilter,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [
            new DailyAttendance(),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new Confirm)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm attendances') || $request->user()->isSuperAdmin();
            })->canRun(function ($request) {
                return $request->user()->hasPermissionTo('can confirm attendances') || $request->user()->isSuperAdmin();
            })
                ->confirmButtonText('Confirm')
                ->confirmText('Are you sure want to confirm?'),

            (new CheckOut)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('update attendances') || $request->user()->isSuperAdmin();
            })->canRun(function ($request) {
                return $request->user()->hasPermissionTo('update attendances') || $request->user()->isSuperAdmin();
            })
                ->confirmButtonText('Check Out'),

            (new BulkAttendance)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can take bulk attendances') && !$request->user()->isSuperAdmin();
            })->canRun(function ($request) {
                return $request->user()->hasPermissionTo('can take bulk attendances') && !$request->user()->isSuperAdmin();
            })
                ->confirmButtonText('Take Attendance')
                ->confirmText('Are you sure want to take a bulk attendance?')
                ->standalone(),

            (new BulkAttendanceAdmin)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can take bulk attendances') && ($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('create all locations data'));
            })->canRun(function ($request) {
                return $request->user()->hasPermissionTo('can take bulk attendances') && ($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('create all locations data'));
            })
                ->confirmButtonText('Take Attendance')
                ->standalone(),
        ];
    }

    /**
     * Return the location to redirect the user after creation.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  resource
     * @return string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }
}
