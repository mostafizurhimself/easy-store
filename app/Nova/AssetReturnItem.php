<?php

namespace App\Nova;

use App\Enums\ReturnStatus;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Number;
use App\Rules\ReturnQuantityRule;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use App\Nova\Filters\ReturnStatusFilter;
use App\Rules\ReturnQuantityRuleForUpdate;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Titasgailius\SearchRelations\SearchesRelations;

class AssetReturnItem extends Resource
{
    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AssetReturnItem::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can download'];

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
        return "Return Items";
    }

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'location' => ['name'],
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
            BelongsTo::make('Invoice', 'invoice', \App\Nova\AssetReturnINvoice::class)
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Asset')
                ->searchable()
                ->sortable(),

            Number::make('Quantity')
                ->rules('required', 'numeric', 'min:0')
                ->creationRules(new ReturnQuantityRule(\App\Nova\AssetReturnItem::uriKey(), $request->get('asset')))
                ->updateRules(new ReturnQuantityRuleForUpdate(\App\Nova\AssetReturnItem::uriKey(), $request->get('asset'), $this->resource->quantity, $this->resource->assetId))
                ->onlyOnForms(),

            Text::make('Quantity', function () {
                return $this->quantity . " " . $this->unitName;
            })
                ->sortable()
                ->exceptOnForms(),



            Currency::make('Rate')
                ->currency('BDT')
                ->sortable()
                ->help("Leave blank if you don't want to change the default rate."),

            Currency::make('Amount')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),

            Files::make('Attachments', 'return-item-attachments')
                ->hideFromIndex(),

            Trix::make('Note')
                ->rules('nullable', 'max:500'),

            Badge::make('Status')->map([
                ReturnStatus::DRAFT()->getValue()     => 'warning',
                ReturnStatus::CONFIRMED()->getValue() => 'info',
                ReturnStatus::BILLED()->getValue()    => 'danger',
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
            new ReturnStatusFilter,
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
            $invoice = \App\Models\AssetReturnInvoice::find($request->viaResourceId);
            if (empty($invoice)) {
                $invoice = \App\Models\AssetReturnItem::find($request->resourceId)->invoice;
            }
            try {
                $assetId = $request->findResourceOrFail()->assetId;
            } catch (\Throwable $th) {
                $assetId = null;
            }
            return $query->whereHas('suppliers', function ($supplier) use ($invoice) {
                $supplier->where('supplier_id', $invoice->supplierId)
                    ->where('location_id', $invoice->locationId);
            })->whereNotIn('id', $invoice->assetIds($assetId));
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

        return $query->with('invoice', 'asset', 'unit');
    }
}