<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Actions\AssetConsumes\Discard;
use Laravel\Nova\Http\Requests\NovaRequest;

class AssetConsume extends Resource
{
    use WithOutLocation;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AssetConsume::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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
            Text::make('Consume Id', 'readable_id'),

            BelongsTo::make('Asset')
            ->sortable()
            ->searchable(),

            Text::make('Quantity', function(){
                return $this->quantity ." ".$this->unitName;
            })->sortable(),

            Currency::make('Rate')->sortable()
                    ->currency("BDT"),

            Currency::make('Amount')->sortable()
                    ->currency("BDT"),

            Text::make('Consumer', function(){
                return $this->user->name;
            })->sortable(),

            DateTime::make('Consumed At', 'created_at')->sortable(),


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
            (new Discard)->canSee(function($request){
                return $request->user()->isSuperAdmin();
            })->canRun(function($request){
                return $request->user()->isSuperAdmin();
            })
            ->onlyOnTableRow()
            ->confirmText("Are you sure want to discard this consume?")
            ->confirmButtonText('Discard'),
        ];
    }
}
