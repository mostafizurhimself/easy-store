<?php

namespace App\Nova;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\DispatchStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use App\Nova\Filters\DateRangeFilter;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Rules\ServiceReceiveQuantityRule;
use App\Nova\Filters\DispatchStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use App\Rules\ServiceReceiveQuantityRuleForUpdate;
use App\Nova\Actions\ServiceReceives\ConfirmReceive;

class ServiceReceive extends Resource
{
    use WithOutLocation;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ServiceReceive::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can download'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'readable_id';

    /**
     * Hide resource from Nova's standard menu.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * Indicates if the resource should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = false;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'readable_id',
    ];

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return "Receive";
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
            BelongsTo::make('Invoice', 'invoice', "App\Nova\ServiceInvoice")
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Dispatch', 'dispatch', "App\Nova\ServiceDispatch")
                ->onlyOnDetail()
                ->sortable(),

            BelongsTo::make('Service')
                ->exceptOnForms()
                ->sortable(),

            Date::make('Date')
                ->rules('required')
                ->default(Carbon::now())
                ->sortable()
                ->readonly(),

            Hidden::make('Date')
                ->default(Carbon::now())
                ->sortable()
                ->hideWhenUpdating(),

            Number::make('Quantity')
                ->rules('required', 'numeric', 'min:0')
                ->creationRules(new ServiceReceiveQuantityRule($request->viaResource, $request->viaResourceId))
                ->updateRules(new ServiceReceiveQuantityRuleForUpdate(\App\Nova\ServiceDispatch::uriKey(), $this->resource->dispatchId, $this->resource->quantity))
                ->onlyOnForms()
                ->default(function ($request) {
                    if ($request->viaResource == \App\Nova\ServiceDispatch::uriKey() && !empty($request->viaResourceId)) {
                        return \App\Models\ServiceDispatch::find($request->viaResourceId)->remainingQuantity;
                    }
                }),


            Text::make('Quantity', function () {
                return $this->quantity . " " . $this->unitName;
            })
                ->sortable()
                ->exceptOnForms(),



            Currency::make('Rate')
                ->currency('BDT')
                ->sortable()
                ->default(function ($request) {
                    if ($request->viaResource == \App\Nova\ServiceDispatch::uriKey() && !empty($request->viaResourceId)) {
                        return \App\Models\ServiceDispatch::find($request->viaResourceId)->rate;
                    }
                }),

            Currency::make('Amount')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),

            Text::make("Reference")
                ->hideFromIndex()
                ->rules('required', 'string', 'max:200')
                ->sortable()
                ->help("You can input the provider invoice no here."),

            Files::make('Attachments', 'receive-service-attachments')
                ->hideFromIndex(),

            Trix::make('Note')
                ->rules('nullable', 'max:500'),

            Badge::make('Status')->map([
                DispatchStatus::DRAFT()->getValue()     => 'warning',
                DispatchStatus::CONFIRMED()->getValue() => 'info',
                DispatchStatus::PARTIAL()->getValue()   => 'danger',
                DispatchStatus::RECEIVED()->getValue()  => 'success',
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
        return [
            new DateRangeFilter('date'),

            new DispatchStatusFilter,
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
            (new ConfirmReceive)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm service receives');
            }),
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
        return '/resources/' . $request->viaResource . "/" . $request->viaResourceId;
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
        if (isset($request->viaResource) && isset($request->viaResourceId)) {
            return '/resources/' . $request->viaResource . "/" . $request->viaResourceId;
        }

        return '/resources/' . $resource->uriKey() . "/" . $resource->id;
    }
}
