<?php

namespace App\Nova;

use Carbon\Carbon;
use App\Models\Asset;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\PurchaseStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Filters\AssetFilter;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Markdown;
use App\Rules\ReceiveQuantityRule;
use Laravel\Nova\Fields\BelongsTo;
use Treestoneit\TextWrap\TextWrap;
use Easystore\RouterLink\RouterLink;
use AwesomeNova\Filters\DependentFilter;
use App\Nova\Filters\PurchaseStatusFilter;
use App\Rules\ReceiveQuantityRuleForUpdate;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\BelongsToLocationFilter;
use App\Nova\Filters\BelongsToSupplierFilter;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use PosLifestyle\DateRangeFilter\DateRangeFilter;
use App\Nova\Actions\AssetReceiveItems\DownloadPdf;
use App\Nova\Actions\AssetReceiveItems\MarkAsDraft;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\AssetReceiveItems\DownloadExcel;
use App\Nova\Filters\BelongsToDependentLocationFilter;
use App\Nova\Actions\AssetReceiveItems\ConfirmReceiveItem;

class AssetReceiveItem extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AssetReceiveItem::class;

    /**
     * The number of resources to show per page via relationships.
     *
     * @var int
     */
    public static $perPageViaRelationship = 10;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can download', 'can mark as draft'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

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
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Receive Items";
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

            Date::make('Date')
                ->rules('required')
                ->default(Carbon::now())
                ->sortable()
                ->readonly(),

            BelongsTo::make('PO Number', 'purchaseOrder', "App\Nova\AssetPurchaseOrder")
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Asset')
                ->hideWhenCreating()
                ->sortable()
                ->readonly(),

            Hidden::make('Date')
                ->default(Carbon::now())
                ->hideWhenUpdating(),

            Number::make('Quantity')
                ->rules('required', 'numeric', 'min:0')
                ->sortable()
                ->creationRules(function ($request) {
                    if ($request->isCreateOrAttachRequest()) {
                        return [new ReceiveQuantityRule($request->viaResource, $request->viaResourceId)];
                    }
                    return [];
                })
                ->updateRules(function ($request) {
                    if ($request->isUpdateOrUpdateAttachedRequest()) {
                        return [new ReceiveQuantityRuleForUpdate(\App\Nova\AssetPurchaseItem::uriKey(), $this->resource->purchaseItemId, $this->resource->quantity)];
                    }
                    return [];
                })
                ->onlyOnForms()
                ->default(function ($request) {
                    if ($request->viaResource == \App\Nova\AssetPurchaseItem::uriKey() && !empty($request->viaResourceId)) {
                        return \App\Models\AssetPurchaseItem::find($request->viaResourceId)->remainingQuantity;
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
                    if ($request->viaResource == \App\Nova\AssetPurchaseItem::uriKey() && !empty($request->viaResourceId)) {
                        return \App\Models\AssetPurchaseItem::find($request->viaResourceId)->purchaseRate;
                    }
                })
                ->onlyOnForms(),

            Currency::make('Rate')
                ->currency('BDT')
                ->onlyOnDetail()
                ->sortable(),

            Currency::make('Amount')
                ->currency('BDT')
                ->onlyOnDetail()
                ->sortable(),

            Text::make("Reference")
                ->help('Here you can enter the supplier invoice number.')
                // ->hideFromIndex()
                ->sortable()
                ->rules('nullable', 'string', 'max:200'),

            Files::make('Attachments', 'receive-item-attachments')
                ->hideFromIndex(),

            Trix::make('Note')
                ->rules('nullable', 'max:500'),

            TextWrap::make("Supplier", function () {
                return $this->purchaseOrder->supplier->name;
            })
                ->sortable()
                ->exceptOnForms()
                ->wrapMethod('length', 25),

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
            BelongsToDependentLocationFilter::make('Location', 'location_id', 'purchaseOrder')
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
            new BelongsToSupplierFilter('purchaseOrder'),
            new DateRangeFilter('Date Between', 'date'),
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
                return ($request->user()->hasPermissionTo('can download asset receive items') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download asset receive items') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download pdf?"),

            (new DownloadExcel)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download asset receive items') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download asset receive items') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download excel?"),

            (new MarkAsDraft)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can mark as draft asset receive items') || $request->user()->isSuperAdmin();
            })->canRun(function ($request) {
                return $request->user()->hasPermissionTo('can mark as draft asset receive items') || $request->user()->isSuperAdmin();
            })
                ->onlyOnDetail()
                ->confirmButtonText('Mark As Draft'),

            (new ConfirmReceiveItem)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm asset receive items') || $request->user()->isSuperAdmin();
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

        return $query->with('purchaseOrder.location', 'purchaseItem', 'asset', 'unit', 'purchaseOrder.supplier');
    }
}