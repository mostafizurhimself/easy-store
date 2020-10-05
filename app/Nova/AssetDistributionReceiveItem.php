<?php

namespace App\Nova;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use App\Enums\DistributionStatus;
use Laravel\Nova\Fields\Currency;
use App\Rules\ReceiveQuantityRule;
use Laravel\Nova\Fields\BelongsTo;
use App\Rules\ReceiveQuantityRuleForUpdate;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use App\Nova\Actions\AssetDistributionReceiveItem\ConfirmReceiveItem;

class AssetDistributionReceiveItem extends Resource
{
    use WithOutLocation;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AssetDistributionReceiveItem::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can download'];

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
            // ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('Invoice', 'invoice', \App\Nova\AssetDistributionInvoice::class)
                ->exceptOnForms(),

            BelongsTo::make('Asset')
                ->hideWhenCreating()
                ->readonly(),

            Date::make('Date')
                ->rules('required')
                ->default(Carbon::now())
                ->readonly(),

            Hidden::make('Date')
                ->default(Carbon::now())
                ->hideWhenUpdating(),

            Number::make('Quantity')
                ->default(function($request){
                    if($request->viaResource ==  \App\Nova\AssetDistributionItem::uriKey() && !empty($request->viaResourceId)){
                        return \App\Models\AssetDistributionItem::find($request->viaResourceId)->remainingQuantity;
                    }else{
                        return $this->resource->distributionItem->remainingQuantity;
                    }
                })
                ->rules('required', 'numeric', 'min:0')
                ->creationRules(new ReceiveQuantityRule($request->viaResource, $request->viaResourceId))
                ->updateRules(new ReceiveQuantityRuleForUpdate(\App\Nova\AssetDistributionItem::uriKey(), $this->resource->distributionItemId, $this->resource->quantity))
                ->onlyOnForms(),

            Text::make('Quantity', function(){
                    return $this->quantity." ".$this->unit;
                })
                ->exceptOnForms(),

            Currency::make('Rate')
                ->currency('BDT')
                ->exceptOnForms(),

            Currency::make('Amount')
                ->currency('BDT')
                ->exceptOnForms(),

            Text::make("Reference")
                ->hideFromIndex()
                ->rules('nullable', 'string', 'max:200'),

            Files::make('Attachments', 'distribution-receive-item-attachments')
                ->hideFromIndex(),

            Trix::make('Note')
                ->rules('nullable', 'max:500'),

            Badge::make('Status')->map([
                    DistributionStatus::DRAFT()->getValue()     => 'warning',
                    DistributionStatus::CONFIRMED()->getValue() => 'info',
                    DistributionStatus::PARTIAL()->getValue()   => 'danger',
                    DistributionStatus::RECEIVED()->getValue()  => 'success'
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
        return [];
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
            (new ConfirmReceiveItem)->canSee(function($request){
                return $request->user()->hasPermissionTo('can confirm asset distribution receive items');
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
