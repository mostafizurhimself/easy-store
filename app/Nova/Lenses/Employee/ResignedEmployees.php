<?php

namespace App\Nova\Lenses\Employee;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\EmployeeStatus;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Easystore\RouterLink\RouterLink;
use Bissolli\NovaPhoneField\PhoneNumber;
use Laravel\Nova\Http\Requests\LensRequest;

class ResignedEmployees extends Lens
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
            $query->where('location_id', $request->user()->location_id)->whereNotNull('resign_date');
        } else {
            $query->whereNotNull('resign_date');
        }
        return $request->withOrdering($request->withFilters(
            $query
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
            Text::make('ID', function () {
                return $this->readableId;
            }),

            Text::make('Name')
                ->exceptOnForms()
                ->displayUsing(function () {
                    return $this->firstName . " " . $this->lastName;
                })
                ->sortable(),

            PhoneNumber::make('Mobile')
                ->withCustomFormats('+88 ### #### ####')
                ->onlyCustomFormats()
                ->rules('required'),

            BelongsTo::make('Location')
                ->searchable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Department')
                ->exceptOnForms(),

            BelongsTo::make('Designation')
                ->exceptOnForms()
                ->sortable(),

            Badge::make('Status')->map([
                EmployeeStatus::ACTIVE()->getValue()   => 'success',
                EmployeeStatus::VACATION()->getValue() => 'warning',
                EmployeeStatus::INACTIVE()->getValue() => 'danger',
                EmployeeStatus::RESIGNED()->getValue() => 'danger',
            ])
                ->sortable()
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
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
        return 'resigned-employees';
    }
}