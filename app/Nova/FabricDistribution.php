<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\Number;
use App\Enums\DistributionStatus;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Easystore\RouterLink\RouterLink;
use App\Rules\DistributeQuantityRule;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\FabricDistributions\ConfirmDistribution;

class FabricDistribution extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\FabricDistribution';

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">04</span>Fabrics Section';

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
      return 'fas fa-truck';
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
      return "Distributions";
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
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'location' => ['name'],
        'fabric' => ['code'],
        'receiver' => ['readable_id'],
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
            // ID::make()->sortable(),

            BelongsTo::make('Location')
                ->searchable(),

            RouterLink::make('Number', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ]),

            BelongsTo::make('Fabric')
                    ->exceptOnForms(),

            BelongsTo::make('Fabric')
                ->canSee(function($request){
                     if(!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()){
                         return true;
                     }

                     return false;
                })
                ->onlyOnForms(),

            AjaxSelect::make('Fabric', 'fabric_id')
                ->rules('required')
                ->get('/locations/{location}/fabrics')
                ->parent('location')
                ->canSee(function($request){
                    if($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()){
                        return true;
                    }
                    return false;
                })
                ->onlyOnForms(),

            Number::make('Quantity')
                ->creationRules(new DistributeQuantityRule(\App\Nova\FabricDistribution::uriKey(), $request->get('fabric_id')))
                ->rules('required', 'numeric', 'min:1')
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

            Trix::make('Description')
                ->rules('nullable', 'string', 'max:500'),

            BelongsTo::make('Receiver', 'receiver', "App\Nova\Employee")
                ->exceptOnForms(),

            BelongsTo::make('Receiver', 'receiver', "App\Nova\Employee")
                ->canSee(function($request){
                    if(!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()){
                        return true;
                    }

                    return false;
            })
            ->onlyOnForms(),

            AjaxSelect::make('Receiver', 'receiver_id')
                ->rules('required')
                ->get('/locations/{location}/employees')
                ->parent('location')
                ->canSee(function($request){
                    if($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()){
                        return true;
                    }
                    return false;
                })
                ->onlyOnForms(),

            Badge::make('Status')->map([
                    DistributionStatus::DRAFT()->getValue()     => 'warning',
                    DistributionStatus::CONFIRMED()->getValue() => 'info',
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
            new ConfirmDistribution
        ];
    }
}
