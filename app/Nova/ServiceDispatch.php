<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\DispatchStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\BelongsToDateFilter;
use App\Nova\Filters\DispatchStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\BelongsToLocationFilter;
use App\Nova\Actions\ServiceDispatches\DownloadPdf;
use App\Nova\Actions\ServiceDispatches\DownloadExcel;

class ServiceDispatch extends Resource
{
    use WithOutLocation;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ServiceDispatch::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can download'];

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
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Dispatches";
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
            Text::make("Location",function(){
                return $this->location->name;
            })
                ->sortable()
                ->exceptOnForms()
                ->canSee(function($request){
                    return ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin())
                    && (empty($request->viaResource));
                }),

            Date::make('Date', function () {
                return $this->date;
            })
                ->sortable()
                ->exceptOnForms()
                ->canSee(function($request){
                    return empty($request->viaResource);
                }),


            BelongsTo::make('Invoice', 'invoice', "App\Nova\ServiceInvoice")
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Service', 'service', 'App\Nova\Service')
                ->sortable(),

            Number::make('Quantity', 'dispatch_quantity')
                ->rules('required', 'numeric', 'min:1')
                ->sortable()
                ->onlyOnForms(),

            Currency::make('Rate')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),

            Text::make('Dispatch Quantity', function () {
                return $this->dispatchQuantity . " " . $this->unit->name;
            })
                ->sortable()
                ->exceptOnForms(),



            Text::make('Receive Quantity', function () {
                return $this->receiveQuantity . " " . $this->unit->name;
            })
                ->sortable()
                ->exceptOnForms(),

            Text::make('Remaining Quantity', function () {
                return $this->remainingQuantity . " " . $this->unit->name;
            })
                ->sortable()
                ->exceptOnForms(),

            Currency::make('Dispatch Amount')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms()
                ->hideFromIndex(),

            Currency::make('Receive Amount')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms()
                ->hideFromIndex(),

            Trix::make('Description')
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

            HasMany::make('Receive Service', 'receives', 'App\Nova\ServiceReceive'),


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
            (new BelongsToLocationFilter('invoice'))->canSee(function($request){
                return $request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin();
            }),
            new BelongsToDateFilter('invoice'),
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
            (new DownloadPdf)->canSee(function($request){
                return ($request->user()->hasPermissionTo('can download service dispatches') || $request->user()->isSuperAdmin());
            })->canRun(function($request){
                return ($request->user()->hasPermissionTo('can download service dispatches') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download'),

            (new DownloadExcel)->canSee(function($request){
                return ($request->user()->hasPermissionTo('can download service dispatches') || $request->user()->isSuperAdmin());
            })->canRun(function($request){
                return ($request->user()->hasPermissionTo('can download service dispatches') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
            ->confirmText('Are you sure want to download excel?'),
        ];
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableServices(NovaRequest $request, $query)
    {
        $invoice = \App\Models\ServiceInvoice::find($request->viaResourceId);
        if (empty($invoice)) {
            $invoice = $request->findResourceOrFail()->invoice;
        }
        try {
            $serviceId = $request->findResourceOrFail()->serviceId;
        } catch (\Throwable $th) {
            $serviceId = null;
        }
        return $query->whereHas('providers', function ($supplier) use ($invoice) {
            $supplier->where('provider_id', $invoice->providerId)
                ->where('location_id', $invoice->locationId);
        })->whereNotIn('id', $invoice->serviceIds($serviceId));
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
