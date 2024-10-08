<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\PurchaseStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\BelongsToDateFilter;
use App\Nova\Filters\PurchaseStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\BelongsToLocationFilter;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\AssetPurchaseItems\DownloadPdf;
use App\Nova\Actions\AssetPurchaseItems\DownloadExcel;

class AssetPurchaseItem extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AssetPurchaseItem::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'readable_id';

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can download'];

    /**
     * The number of resources to show per page via relationships.
     *
     * @var int
     */
    public static $perPageViaRelationship = 10;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Purchase Items";
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'readable_id',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'purchaseOrder' => ['readable_id'],
        'asset' => ['name', 'code'],
    ];

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
                return $this->location->name;
            })
                ->sortable()
                ->exceptOnForms()
                ->canSee(function ($request) {
                    return ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin())
                        && (empty($request->viaResource));
                }),

            Date::make('Date', function () {
                return $this->date;
            })
                ->sortable()
                ->exceptOnForms()
                ->canSee(function ($request) {
                    return empty($request->viaResource);
                }),

            BelongsTo::make('PO Number', 'purchaseOrder', "App\Nova\AssetPurchaseOrder")
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Asset')
                ->searchable()
                ->sortable(),

            Number::make('Quantity', 'purchase_quantity')
                ->rules('required', 'numeric', 'min:0')
                ->onlyOnForms(),

            Text::make('Purchase Quantity', function () {
                return $this->purchaseQuantity . " " . $this->unitName;
            })
                ->exceptOnForms()
                ->sortable(),

            Text::make('Receive Quantity', function () {
                return $this->receiveQuantity . " " . $this->unitName;
            })
                ->exceptOnForms()
                ->sortable(),

            Currency::make('Purchase Rate')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),

            Currency::make('Purchase Amount')
                ->currency('BDT')
                ->sortable()
                ->onlyOnDetail(),

            Currency::make('Receive Amount')
                ->currency('BDT')
                ->sortable()
                ->onlyOnDetail(),

            Badge::make('Status')->map([
                PurchaseStatus::DRAFT()->getValue()     => 'warning',
                PurchaseStatus::CONFIRMED()->getValue() => 'info',
                PurchaseStatus::PARTIAL()->getValue()   => 'danger',
                PurchaseStatus::RECEIVED()->getValue()  => 'success',
                PurchaseStatus::BILLED()->getValue()    => 'danger',
            ])
                ->sortable()
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            HasMany::make('Receive Items', 'receiveItems', 'App\Nova\AssetReceiveItem'),


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
            (new BelongsToLocationFilter('purchaseOrder'))->canSee(function ($request) {
                return $request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin();
            }),
            new BelongsToDateFilter('purchaseOrder'),
            new PurchaseStatusFilter,
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
            (new DownloadPdf)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download asset purchase items') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download asset purchase items') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download pdf?"),

            (new DownloadExcel)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download asset purchase items') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download asset purchase items') || $request->user()->isSuperAdmin());
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
            $purchase = \App\Models\AssetPurchaseOrder::find($request->viaResourceId);

            if (empty($purchase)) {
                $purchase = $request->findResourceOrFail()->purchaseOrder;
            }
            try {
                $assetId = $request->findResourceOrFail()->assetId;
            } catch (\Throwable $th) {
                $assetId = null;
            }
            return $query->whereHas('suppliers', function ($supplier) use ($purchase) {
                $supplier->where('supplier_id', $purchase->supplierId)
                    ->where('location_id', $purchase->locationId);
            })->whereNotIn('id', $purchase->assetIds($assetId));
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

        return $query->with('purchaseOrder.location', 'asset', 'unit');
    }
}