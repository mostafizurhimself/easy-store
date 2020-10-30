<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\PurchaseStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\BelongsToDateFilter;
use App\Nova\Filters\PurchaseStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\BelongsToLocationFilter;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\MaterialPurchaseItems\DownloadPdf;
use App\Nova\Actions\MaterialPurchaseItems\DownloadExcel;

class MaterialPurchaseItem extends Resource
{
    use WithOutLocation, SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\MaterialPurchaseItem::class;

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
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Purchase Items";
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
        'purchaseOrder' => ['name'],
        'material' => ['name'],
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

            BelongsTo::make('PO Number', 'purchaseOrder', "App\Nova\MaterialPurchaseOrder")
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Material')
                ->searchable()
                ->sortable(),

            Number::make('Quantity', 'purchase_quantity')
                ->rules('required', 'numeric', 'min:0')
                ->onlyOnForms()
                ->sortable(),

            Text::make('Purchase Quantity', function () {
                return $this->purchaseQuantity . " " . $this->unitName;
            })
                ->sortable()
                ->exceptOnForms(),



            Text::make('Receive Quantity', function () {
                return $this->receiveQuantity . " " . $this->unitName;
            })
                ->sortable()
                ->exceptOnForms(),

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

            HasMany::make('Receive Items', 'receiveItems', 'App\Nova\MaterialReceiveItem'),
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
            (new BelongsToLocationFilter('purchaseOrder'))->canSee(function($request){
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
                return ($request->user()->hasPermissionTo('can download material purchase items') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download material purchase items') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download'),

            (new DownloadExcel)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download material purchase items') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download material purchase items') || $request->user()->isSuperAdmin());
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
    public static function relatableMaterials(NovaRequest $request, $query)
    {
        $purchase = \App\Models\MaterialPurchaseOrder::find($request->viaResourceId);

        if (empty($purchase)) {
            $purchase = $request->findResourceOrFail()->purchaseOrder;
        }

        try {
            $materialId = $request->findResourceOrFail()->materialId;
        } catch (\Throwable $th) {
            $materialId = null;
        }
        return $query->whereHas('suppliers', function ($supplier) use ($purchase) {
            $supplier->where('supplier_id', $purchase->supplierId)
                ->where('location_id', $purchase->locationId);
        })->whereNotIn('id', $purchase->materialIds($materialId));
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
