<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\TransferStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Illuminate\Support\Carbon;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Currency;
use App\Rules\ReceiveQuantityRule;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\TransferStatusFilter;
use App\Rules\ReceiveQuantityRuleForUpdate;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use PosLifestyle\DateRangeFilter\DateRangeFilter;
use App\Nova\Actions\FabricTransferReceiveItem\ConfirmReceiveItem;

class FabricTransferReceiveItem extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\FabricTransferReceiveItem::class;

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
    public static $permissions = ['can confirm', 'can download'];

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
     * Hide resource from Nova's standard menu.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * Indicates if the resource should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            // ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('Invoice', 'invoice', \App\Nova\FabricTransferInvoice::class)
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Fabric')
                ->hideWhenCreating()
                ->sortable()
                ->readonly(),

            Date::make('Date')
                ->rules('required')
                ->default(Carbon::now())
                ->sortable()
                ->readonly(),

            Hidden::make('Date')
                ->default(Carbon::now())
                ->hideWhenUpdating(),

            Number::make('Quantity')
                ->default(function ($request) {
                    if ($request->viaResource ==  \App\Nova\FabricTransferItem::uriKey() && !empty($request->viaResourceId)) {
                        return \App\Models\FabricTransferItem::find($request->viaResourceId)->remainingQuantity;
                    } else {
                        return $this->resource->transferItem->remainingQuantity;
                    }
                })
                ->sortable()
                ->rules('required', 'numeric', 'min:0')
                ->creationRules(new ReceiveQuantityRule($request->viaResource, $request->viaResourceId))
                ->updateRules(new ReceiveQuantityRuleForUpdate(\App\Nova\FabricTransferItem::uriKey(), $this->resource->transferItemId, $this->resource->quantity))
                ->onlyOnForms(),

            Text::make('Quantity', function () {
                return $this->quantity . " " . $this->unitName;
            })
                ->exceptOnForms()
                ->sortable(),

            Currency::make('Rate')
                ->currency('BDT')
                ->exceptOnForms()
                ->sortable(),

            Currency::make('Amount')
                ->currency('BDT')
                ->exceptOnForms()
                ->sortable(),

            Text::make("Reference")
                ->hideFromIndex()
                ->rules('nullable', 'string', 'max:200'),

            Files::make('Attachments', 'transfer-receive-item-attachments')
                ->hideFromIndex(),

            Trix::make('Note')
                ->rules('nullable', 'max:500'),

            Badge::make('Status')->map([
                TransferStatus::DRAFT()->getValue()     => 'warning',
                TransferStatus::CONFIRMED()->getValue() => 'info',
                TransferStatus::PARTIAL()->getValue()   => 'danger',
                TransferStatus::RECEIVED()->getValue()  => 'success'
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
            new DateRangeFilter('Date Between', 'date'),
            new TransferStatusFilter,
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
            (new ConfirmReceiveItem)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm fabric transfer receive items');
            })
                ->confirmButtonText('Confirm'),
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

        return $query->with('invoice.location', 'fabric', 'unit');
    }
}