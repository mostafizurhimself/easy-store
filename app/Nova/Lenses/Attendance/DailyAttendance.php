<?php

namespace App\Nova\Lenses\Attendance;

use Carbon\Carbon;
use Michielfb\Time\Time;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use App\Enums\ConfirmStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\LensRequest;

class DailyAttendance extends Lens
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
        return $request->withOrdering($request->withFilters(
            $query->where('date', Carbon::now()->format('Y-m-d'))
        ));
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

            BelongsTo::make('Shift', 'shift', \App\Nova\Shift::class)
                ->exceptOnForms()
                ->sortable(),

            Time::make('In', 'in')
                ->sortable()
                ->format('HH:mm')
                ->required(),

            Time::make('Out', 'out')
                ->format('HH:mm')
                ->sortable()
                ->exceptOnForms(),

            Text::make('Late', 'late')
                ->sortable()
                ->displayUsing(function ($late) {
                    return gmdate("H:i:s", $late);
                })
                ->exceptOnForms(),

            Text::make('Overtime')
                ->sortable()
                ->displayUsing(function ($overtime) {
                    return gmdate("H:i:s", $overtime);
                })
                ->onlyOnDetail(),
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
        return 'attendance-daily-attendance';
    }
}
