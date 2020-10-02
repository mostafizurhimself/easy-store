<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use App\Enums\RequisitionStatus;
use Laravel\Nova\Fields\HasMany;
use App\Enums\DistributionStatus;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Rules\DistributionQuantityRule;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Rules\DistributionQuantityRuleForUpdate;
use App\Rules\DistributionQuantityRuleOnRequisition;
use App\Rules\DistributionQuantityRuleOnRequisitionForUpdate;

class AssetDistributionItem extends Resource
{
    use WithOutLocation;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AssetDistributionItem::class;

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 7;

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">06</span>Asset Section';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'readable_id';

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
      return "Distribution Items";
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
            BelongsTo::make('Invoice', 'invoice', "App\Nova\AssetDistributionInvoice")
                ->onlyOnDetail(),

            BelongsTo::make('Asset'),

            Number::make('Quantity', 'distribution_quantity')
                ->rules('required', 'numeric', 'min:0')
                ->creationRules(new DistributionQuantityRule(\App\Nova\AssetDistributionItem::uriKey(), $request->get('asset_id') ?? $request->get('asset')),
                                new DistributionQuantityRuleOnRequisition($request->viaResource, $request->viaResourceId, $request->get('asset_id') ?? $request->get('asset')),
                )
                ->updateRules(new DistributionQuantityRuleForUpdate(\App\Nova\AssetDistributionItem::uriKey(),
                                                                    $request->get('asset_id'),
                                                                    $this->resource->distributionQuantity,
                                                                    $this->resource->assetId),
                            new DistributionQuantityRuleOnRequisitionForUpdate($request->viaResource,
                                                                    $request->viaResourceId,
                                                                    $request->get('asset_id'),
                                                                    $this->resource->assetId,
                                                                    $this->resource->distributionQuantity),
                            )
                ->onlyOnForms(),

            Text::make('Distribution Quantity', function(){
                return $this->distributionQuantity." ".$this->unit;
            })
            ->exceptOnForms(),

            Currency::make('Distribution Rate')
                ->currency('BDT')
                ->exceptOnForms(),

            Currency::make('Distribution Amount')
                ->currency('BDT')
                ->exceptOnForms(),

            Text::make('Receive Quantity', function(){
                    return $this->receiveQuantity." ".$this->unit;
                })
                ->exceptOnForms(),

            Currency::make('Receive Amount')
                ->currency('BDT')
                ->onlyOnDetail(),

            Badge::make('Status')->map([
                    DistributionStatus::DRAFT()->getValue()     => 'warning',
                    DistributionStatus::CONFIRMED()->getValue() => 'info',
                    DistributionStatus::PARTIAL()->getValue()   => 'danger',
                    DistributionStatus::RECEIVED()->getValue()  => 'success',
                ])
                ->label(function(){
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            HasMany::make('Receive Items', 'receiveItems', \App\Nova\AssetDistributionReceiveItem::class)
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
    public static function relatableAssets(NovaRequest $request, $query)
    {
        $invoice = \App\Models\AssetDistributionInvoice::find($request->viaResourceId);

        if(empty($invoice)){
            $invoice = $request->findResourceOrFail()->invoice;
        }

        try {
            $assetId = $request->findResourceOrFail()->assetId;
        } catch (\Throwable $th) {
           $assetId = null;
        }

        if(!empty($invoice->requisitionId)){
            return $query->whereIn('id',$invoice->requisition->assetIds())
                ->whereNotIn('id', $invoice->assetIds($assetId))->get()->map(function($asset){
                    return [ 'value' => $asset->id, 'label' => $asset->name."({$asset->code})" ];
                });
        }

        return $query->where('location_id', $invoice->locationId)
                ->whereNotIn('id', $invoice->assetIds($assetId))->get()->map(function($asset){
                    return [ 'value' => $asset->id, 'label' => $asset->name."({$asset->code})" ];
                });
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
