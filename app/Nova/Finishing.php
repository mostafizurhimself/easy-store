<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use App\Enums\FinishingStatus;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class Finishing extends Resource
{
    use WithOutLocation;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Finishing::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can download'];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">08</span>Product Section';

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 5;

    /**
     * Hide resource from Nova's standard menu.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
      return 'fas fa-gift';
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'readable_id';

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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            BelongsTo::make('Invoice', 'invoice', "App\Nova\FinishingInvoice")
                ->exceptOnForms(),

            BelongsTo::make('Product')->searchable(),

            BelongsTo::make('Style')
                ->searchable()
                ->showCreateRelationButton(),

            Number::make('Quantity')
                ->rules('required', 'numeric', 'min:0')
                ->onlyOnForms(),

            Text::make('Quantity', function(){
                    return $this->quantity." ".$this->unit;
                })
                ->exceptOnForms(),

            Currency::make('Rate')
                ->currency('BDT')
                ->exceptOnForms(),

            Currency::make('Amount')
                ->currency('BDT')
                ->exceptOnForms(),

            Badge::make('Status')->map([
                    FinishingStatus::DRAFT()->getValue()     => 'warning',
                    FinishingStatus::CONFIRMED()->getValue() => 'info',
                    FinishingStatus::ADDED()->getValue()   => 'success',
                ])
                ->label(function(){
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
        return [];
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
    public static function relatableProducts(NovaRequest $request, $query)
    {
        $invoice = \App\Models\FinishingInvoice::find($request->viaResourceId);
        if(empty($invoice)){
            $invoice = $request->findResourceOrFail()->invoice;
        }
        try {
            $productId = $request->findResourceOrFail()->productId;
        } catch (\Throwable $th) {
           $productId = null;
        }
        return $query->where('location_id', $invoice->locationId)
                    ->whereNotIn('id', $invoice->productIds($productId));
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
    public static function relatableStyles(NovaRequest $request, $query)
    {
        $invoice = \App\Models\FinishingInvoice::find($request->viaResourceId);

        return $query->where('location_id', $invoice->locationId);
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
        return '/resources/'.$request->viaResource."/".$request->viaResourceId;
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
        if(isset($request->viaResource) && isset($request->viaResourceId)){
            return '/resources/'.$request->viaResource."/".$request->viaResourceId;
        }

        return '/resources/'.$resource->uriKey()."/".$resource->id;
    }
}
