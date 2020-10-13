<?php

namespace App\Nova;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\TransferStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use App\Nova\Filters\DateFilter;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Rules\ServiceReceiveQuantityRule;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use App\Rules\ServiceReceiveQuantityRuleForUpdate;
use App\Nova\Actions\ServiceTransferReceiveItem\ConfirmReceive;
use App\Nova\Filters\TransferStatusFilter;

class ServiceTransferReceiveItem extends Resource
{
    use WithOutLocation;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ServiceTransferReceiveItem::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can download'];

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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            BelongsTo::make('Invoice', 'invoice', \App\Nova\ServiceTransferInvoice::class)
                ->exceptOnForms(),

            BelongsTo::make('Transfer No', 'transfer', \App\Nova\ServiceTransferItem::class)
                ->onlyOnDetail(),

            BelongsTo::make('Service')
                ->exceptOnForms(),

            Date::make('Date')
                ->rules('required')
                ->default(Carbon::now())
                ->readonly(),

            Hidden::make('Date')
                ->default(Carbon::now())
                ->hideWhenUpdating(),

            Number::make('Quantity')
                ->rules('required', 'numeric', 'min:0')
                ->creationRules(new ServiceReceiveQuantityRule($request->viaResource, $request->viaResourceId))
                ->updateRules(new ServiceReceiveQuantityRuleForUpdate(\App\Nova\ServiceTransferItem::uriKey(), $this->resource->dispatchId, $this->resource->quantity))
                ->onlyOnForms()
                ->default(function($request){
                    if($request->viaResource == \App\Nova\ServiceTransferItem::uriKey() && !empty($request->viaResourceId)){
                        return \App\Models\ServiceTransferItem::find($request->viaResourceId)->remainingQuantity;
                    }
                }),


            Text::make('Quantity', function(){
                    return $this->quantity." ".$this->unitName;
                })
                ->exceptOnForms(),



            Currency::make('Rate')
                ->currency('BDT')
                ->default(function($request){
                    if($request->viaResource == \App\Nova\ServiceTransferItem::uriKey() && !empty($request->viaResourceId)){
                        return \App\Models\ServiceTransferItem::find($request->viaResourceId)->rate;
                    }
                }),

            Currency::make('Amount')
                ->currency('BDT')
                ->exceptOnForms(),

            Files::make('Attachments', 'receive-service-attachments')
                ->hideFromIndex(),

            Trix::make('Note')
                ->rules('nullable', 'max:500'),

            Badge::make('Status')->map([
                    TransferStatus::DRAFT()->getValue()     => 'warning',
                    TransferStatus::CONFIRMED()->getValue() => 'info',
                    TransferStatus::PARTIAL()->getValue()   => 'danger',
                    TransferStatus::RECEIVED()->getValue()  => 'success',
                ])
                ->label(function(){
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
            new DateFilter('date'),

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
            (new ConfirmReceive)->canSee(function($request){
                return $request->user()->hasPermissionTo('can confirm service transfer receive items');
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
