<?php

namespace App\Nova;

use App\Models\Asset;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use App\Enums\DistributionStatus;
use App\Nova\Filters\AssetFilter;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Treestoneit\TextWrap\TextWrap;
use App\Rules\DistributionQuantityRule;
use AwesomeNova\Filters\DependentFilter;
use App\Nova\Filters\BelongsToDateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\BelongsToReceiverFilter;
use App\Nova\Filters\DistributionStatusFilter;
use App\Rules\DistributionQuantityRuleForUpdate;
use App\Rules\DistributionQuantityRuleOnRequisition;
use App\Nova\Filters\BelongsToDependentLocationFilter;
use App\Nova\Actions\AssetDistributionItems\AutoReceive;
use App\Nova\Actions\AssetDistributionItems\DownloadPdf;
use App\Nova\Actions\AssetDistributionItems\DownloadExcel;
use App\Rules\DistributionQuantityRuleOnRequisitionForUpdate;

class AssetDistributionItem extends Resource
{
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
    public static $permissions = ['can download', 'can auto receive'];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Asset Section';

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
            Text::make("Location", function () {
                return $this->invoice->location->name;
            })
                ->sortable()
                ->exceptOnForms()
                ->canSee(function ($request) {
                    return ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin())
                        && (empty($request->viaResource));
                }),

            BelongsTo::make('Invoice', 'invoice', "App\Nova\AssetDistributionInvoice")
                ->exceptOnForms()
                ->sortable(),

            Date::make('Date', function () {
                return $this->invoice->date->format('Y-m-d');
            })
                ->sortable()
                ->exceptOnForms(),

            BelongsTo::make('Asset')
                ->searchable()
                ->sortable(),

            Number::make('Quantity', 'distribution_quantity')
                ->rules('required', 'numeric', 'min:0')
                ->sortable()
                ->creationRules(
                    function ($request) {
                        if ($request->isCreateOrAttachRequest()) {
                            return [
                                new DistributionQuantityRule(\App\Nova\AssetDistributionItem::uriKey(), $request->get('asset_id') ?? $request->get('asset')),
                                new DistributionQuantityRuleOnRequisition($request->viaResource, $request->viaResourceId, $request->get('asset_id') ?? $request->get('asset')),
                            ];
                        }
                        return [];
                    }
                )
                ->updateRules(
                    function ($request) {
                        if ($request->isUpdateOrUpdateAttachedRequest()) {
                            return [
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
                            ];
                        }
                        return [];
                    }
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
                ->onlyOnDetail(),

            Currency::make('Distribution Amount')
                ->currency('BDT')
                ->sortable()
                ->onlyOnDetail(),

            Text::make('Receive Quantity', function () {
                return $this->receiveQuantity . " " . $this->unitName;
            })
                ->exceptOnForms()
                ->sortable(),

            Currency::make('Receive Amount')
                ->currency('BDT')
                ->sortable()
                ->onlyOnDetail(),

            TextWrap::make("Receiver", function () {
                return $this->invoice->receiver->name;
            })
                ->sortable()
                ->exceptOnForms()
                ->wrapMethod('length', 25),

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
            BelongsToDependentLocationFilter::make('Location', 'location_id', 'invoice')
                ->canSee(function ($request) {
                    return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
                }),

            DependentFilter::make('Asset', 'asset_id')
                ->dependentOf('location_id')
                ->withOptions(function (Request $request, $filters) {
                    return Asset::where('location_id', $filters['location_id'])
                        ->orderBy('name')
                        ->pluck('name', 'id');
                })->canSee(function ($request) {
                    return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
                }),

            (new AssetFilter)->canSee(function ($request) {
                return !($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data'));
            }),

            new BelongsToDateFilter('invoice'),

            new BelongsToReceiverFilter('invoice'),

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
        return [
            (new AutoReceive)->canRun(function ($request) {
                return $request->user()->hasPermissionTo('can auto receive asset distribution items') || $request->user()->isSuperAdmin();
            })->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can auto receive asset distribution items') || $request->user()->isSuperAdmin();
            })
                ->confirmText('Are you sure want to auto receive?')
                ->confirmButtonText('Auto Receive'),

            (new DownloadPdf)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download asset distribution items') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download asset distribution items') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download pdf?"),

            (new DownloadExcel)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download asset distribution items') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download asset distribution items') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download excel?"),
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

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];

            $query->orderBy(key(static::$sort), reset(static::$sort));
        }

        return $query->with('invoice.location', 'asset', 'unit', 'invoice.receiver');
    }
}