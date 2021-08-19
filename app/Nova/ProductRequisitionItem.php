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
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\RequisitionStatusFilter;
use Titasgailius\SearchRelations\SearchesRelations;

class ProductRequisitionItem extends Resource
{
    use WithOutLocation, SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ProductRequisitionItem::class;

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
    public static $group = 'Product Section';

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
        return "Requisition Items";
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
        'product' => ['name'],
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
            BelongsTo::make('Requisition', 'requisition', \App\Nova\ProductRequisition::class)
                ->onlyOnDetail()
                ->sortable(),

            Text::make('Product', function () {
                return $this->product->name . "({$this->product->code})";
            })
                ->sortable()
                ->exceptOnForms(),

            Select::make('Product', 'product_id')
                ->options(function () {
                    //Get the requisition from request on create
                    $requisition = \App\Models\ProductRequisition::find(request()->viaResourceId);

                    //Get the requisition without request/after create
                    if (empty($requisition)) {
                        $requisition = \App\Models\ProductRequisition::find($this->resource->requisitionId);
                    }

                    try {
                        $productId = request()->findResourceOrFail()->productId;
                    } catch (\Throwable $th) {
                        $productId = null;
                    }
                    return \App\Models\Product::where('location_id', $requisition->receiverId)
                        ->whereNotIn('id', $requisition->productIds($productId))->get()->map(function ($product) {
                            return ['value' => $product->id, 'label' => $product->name . "({$product->code})"];
                        });
                })
                ->rules('required')
                ->sortable()
                ->searchable()
                ->onlyOnForms(),

            Number::make('Quantity', 'requisition_quantity')
                ->rules('required', 'numeric', 'min:0')
                ->onlyOnForms(),

            Text::make('Requisition Quantity', function () {
                return $this->requisitionQuantity . " " . $this->unitName;
            })
                ->sortable()
                ->exceptOnForms(),



            Currency::make('Requisition Rate')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),

            Currency::make('Requisition Amount')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),

            Badge::make('Status')->map([
                RequisitionStatus::DRAFT()->getValue()     => 'warning',
                RequisitionStatus::CONFIRMED()->getValue() => 'info',
                RequisitionStatus::PARTIAL()->getValue()   => 'danger',
                RequisitionStatus::DISTRIBUTED()->getValue()  => 'success',
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
            new RequisitionStatusFilter,
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