<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use App\Enums\RequisitionStatus;
use Laravel\Nova\Fields\HasMany;
use App\Enums\DistributionStatus;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Rules\DistributionQuantityRule;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\DistributionStatusFilter;
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
     * The number of resources to show per page via relationships.
     *
     * @var int
     */
    public static $perPageViaRelationship = 10;

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 7;

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
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Asset')
                ->searchable()
                ->sortable(),

            Number::make('Quantity', 'distribution_quantity')
                ->rules('required', 'numeric', 'min:0')
                ->sortable()
                ->creationRules(
                    new DistributionQuantityRule(\App\Nova\AssetDistributionItem::uriKey(), $request->get('asset_id') ?? $request->get('asset')),
                    new DistributionQuantityRuleOnRequisition($request->viaResource, $request->viaResourceId, $request->get('asset_id') ?? $request->get('asset')),
                )
                ->updateRules(
                    new DistributionQuantityRuleForUpdate(
                        \App\Nova\AssetDistributionItem::uriKey(),
                        $request->get('asset_id') ?? $request->get('asset'),
                        $this->resource->distributionQuantity,
                        $this->resource->assetId
                    ),
                    new DistributionQuantityRuleOnRequisitionForUpdate(
                        $request->viaResource,
                        $request->viaResourceId,
                        $request->get('asset_id') ?? $request->get('asset'),
                        $this->resource->assetId,
                        $this->resource->distributionQuantity
                    ),
                )
                ->onlyOnForms(),

            Text::make('Distribution Quantity', function () {
                return $this->distributionQuantity . " " . $this->unitName;
            })
                ->exceptOnForms()
                ->sortable(),

            Currency::make('Distribution Rate')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),

            Currency::make('Distribution Amount')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),



            Text::make('Receive Quantity', function () {
                return $this->receiveQuantity . " " . $this->unitName;
            })
                ->exceptOnForms()
                ->sortable(),

            Currency::make('Receive Amount')
                ->currency('BDT')
                ->sortable()
                ->onlyOnDetail(),

            Badge::make('Status')->map([
                DistributionStatus::DRAFT()->getValue()     => 'warning',
                DistributionStatus::CONFIRMED()->getValue() => 'info',
                DistributionStatus::PARTIAL()->getValue()   => 'danger',
                DistributionStatus::RECEIVED()->getValue()  => 'success',
            ])
                ->sortable()
                ->label(function () {
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
        return [
            new DistributionStatusFilter,
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
        if (!$request->isResourceIndexRequest()) {
            $invoice = \App\Models\AssetDistributionInvoice::find($request->viaResourceId);

            if (empty($invoice)) {
                $invoice = $request->findResourceOrFail()->invoice;
            }

            try {
                $assetId = $request->findResourceOrFail()->assetId;
            } catch (\Throwable $th) {
                $assetId = null;
            }

            if (!empty($invoice->requisitionId)) {
                return $query->whereIn('id', $invoice->requisition->assetIds())
                    ->whereNotIn('id', $invoice->assetIds($assetId))->get()->map(function ($asset) {
                        return ['value' => $asset->id, 'label' => $asset->name . "({$asset->code})"];
                    });
            }

            return $query->where('location_id', $invoice->locationId)
                ->whereNotIn('id', $invoice->assetIds($assetId))->get()->map(function ($asset) {
                    return ['value' => $asset->id, 'label' => $asset->name . "({$asset->code})"];
                });
        }
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
