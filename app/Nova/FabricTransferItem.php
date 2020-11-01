<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\TransferStatus;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Rules\DistributionQuantityRule;
use App\Nova\Filters\TransferStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Rules\DistributionQuantityRuleForUpdate;

class FabricTransferItem extends Resource
{
    use WithOutLocation;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\FabricTransferItem::class;

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
    public static $permissions = ['can download'];

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
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Tranfer Items";
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            BelongsTo::make('Invoice', 'invoice', \App\Nova\FabricTransferInvoice::class)
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Fabric')
                ->searchable()
                ->sortable(),

            Number::make('Quantity', 'transfer_quantity')
                ->rules('required', 'numeric', 'min:0')
                ->sortable()
                ->creationRules(new DistributionQuantityRule(\App\Nova\FabricTransferItem::uriKey(), $request->get('fabric_id') ?? $request->get('fabric')))
                ->updateRules(new DistributionQuantityRuleForUpdate(
                    \App\Nova\FabricTransferItem::uriKey(),
                    $request->get('fabric_id'),
                    $this->resource->transferQuantity,
                    $this->resource->fabricId
                ))
                ->onlyOnForms(),

            Text::make('Transfer Quantity', function () {
                return $this->transferQuantity . " " . $this->unitName;
            })
                ->exceptOnForms()
                ->sortable(),

            Currency::make('Transfer Rate')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),

            Currency::make('Transfer Amount')
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
                TransferStatus::DRAFT()->getValue()     => 'warning',
                TransferStatus::CONFIRMED()->getValue() => 'info',
                TransferStatus::PARTIAL()->getValue()   => 'danger',
                TransferStatus::RECEIVED()->getValue()  => 'success',
            ])
                ->sortable()
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            HasMany::make('Receive Items', 'receiveItems', \App\Nova\FabricTransferReceiveItem::class)
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
        $invoice = \App\Models\FabricTransferInvoice::find($request->viaResourceId);

        if(empty($invoice)){
            $invoice = $request->findResourceOrFail()->invoice;
        }

        try {
            $fabricId = $request->findResourceOrFail()->fabricId;
        } catch (\Throwable $th) {
           $fabricId = null;
        }

        return $query->where('location_id', $invoice->locationId)
                ->whereNotIn('id', $invoice->fabricIds($fabricId))->get()->map(function($fabric){
                    return [ 'value' => $fabric->id, 'label' => $fabric->name."({$fabric->code})" ];
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
