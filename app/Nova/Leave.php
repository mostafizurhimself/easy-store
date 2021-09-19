<?php

namespace App\Nova;

use Carbon\Carbon;
use App\Enums\LeaveStatus;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use App\Rules\SameMonthRule;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Actions\Leaves\Approve;
use App\Nova\Actions\Leaves\Confirm;
use Laravel\Nova\Http\Requests\NovaRequest;

class Leave extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Leave::class;

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 3;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can approve', 'can download'];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Time Section';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Indicates if the resource should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = false;

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return "Leave";
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-sign-out-alt';
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
                    if (!($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data'))) {
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

            Trix::make('Reason')
                ->rules('required', 'max: 1000'),

            Date::make('From')
                ->sortable()
                ->rules('required'),

            Date::make('To')
                ->sortable()
                ->rules('required', 'after_or_equal:from', new SameMonthRule(request()->get('from'))),

            Text::make('Total Days', function () {
                return $this->totalDays . " days";
            })
                ->exceptOnForms(),


            Text::make('Approved By', function () {
                return $this->approve()->exists() ? $this->approve->employee->name : null;
            })
                ->canSee(function () {
                    return $this->approve()->exists();
                })
                ->onlyOnDetail()
                ->sortable(),

            Hidden::make('Total Days')
                ->fillUsing(function ($request, $model) {
                    $from = Carbon::parse($request->get('from'));
                    $to   = Carbon::parse($request->get('to'));
                    $model['total_days'] = $from->diffInDays($to) + 1;
                }),

            Badge::make('Status')->map([
                LeaveStatus::DRAFT()->getValue()     => 'warning',
                LeaveStatus::CONFIRMED()->getValue() => 'info',
                LeaveStatus::APPROVED()->getValue()  => 'success',
            ])
                ->sortable()
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
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
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
                return ($request->user()->hasPermissionTo('can confirm leaves') || $request->user()->isSuperAdmin());
            })
                ->confirmButtonText('Confirm')
                ->confirmText('Are you sure want to confirm?'),

            (new Approve)->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can approve leaves') || $request->user()->isSuperAdmin());
            })
                ->canRun(function ($request) {
                    return ($request->user()->hasPermissionTo('can approve leaves') || $request->user()->isSuperAdmin());
                })
                ->confirmButtonText('Approve')
                ->confirmText('Are you sure want to approve?'),
        ];
    }
}