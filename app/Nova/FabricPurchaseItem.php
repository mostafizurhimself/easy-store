<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\PurchaseStatus;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class FabricPurchaseItem extends Resource
{
    use WithOutLocation;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\FabricPurchaseItem';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'readable_id';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
      return "Purchase Item";
    }

    /**
     * Indicates if the resource should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = false;

    /**
     * Hide resource from Nova's standard menu.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            // ID::make()->sortable(),

            BelongsTo::make('PO Number', 'purchaseOrder', "App\Nova\FabricPurchaseOrder")
                ->onlyOnDetail(),

            BelongsTo::make('Fabric'),

            Number::make('Quantity', 'purchase_quantity')
                ->rules('required', 'numeric', 'min:0')
                ->onlyOnForms(),

            Text::make('Purchase Quantity', function(){
                return $this->purchaseQuantity." ".$this->unit;
            })
            ->exceptOnForms(),

            Text::make('Receive Quantity', function(){
                return $this->receiveQuantity." ".$this->unit;
            })
            ->exceptOnForms(),

            Currency::make('Purchase Rate')
                ->currency('BDT')
                ->exceptOnForms(),

            Currency::make('Purchase Amount')
                ->currency('BDT')
                ->exceptOnForms(),

            Currency::make('Receive Amount')
                ->currency('BDT')
                ->onlyOnDetail(),

            Badge::make('Status')->map([
                    PurchaseStatus::DRAFT()->getValue()     => 'warning',
                    PurchaseStatus::CONFIRMED()->getValue() => 'info',
                    PurchaseStatus::PARTIAL()->getValue()   => 'danger',
                    PurchaseStatus::RECEIVED()->getValue()  => 'success',
                    PurchaseStatus::BILLED()->getValue()    => 'danger',
                ])
                ->label(function(){
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            HasMany::make('Receive Items', 'receiveItems', 'App\Nova\FabricReceiveItem'),


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
    public static function relatableFabrics(NovaRequest $request, $query)
    {
        $purchase = \App\Models\FabricPurchaseOrder::find($request->viaResourceId);
        try {
            $fabricId = $request->findResourceOrFail()->fabricId;
        } catch (\Throwable $th) {
           $fabricId = null;
        }
        return $query->whereHas('suppliers', function($supplier) use($purchase){
                $supplier->where('supplier_id', $purchase->supplierId)
                        ->where('location_id', $purchase->locationId);
        })->whereNotIn('id', $purchase->fabricIds($fabricId));
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
