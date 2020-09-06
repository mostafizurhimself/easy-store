<?php

namespace App\Nova;

use Carbon\Carbon;
use App\Enums\OutputStatus;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;

class ProductOutput extends Resource
{
    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ProductOutput::class;

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">08</span>Product Section';

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
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'location' => ['name'],
        'category' => ['name'],
        'style' => ['code'],
        'section' => ['name'],
        'subSection' => ['name'],
    ];
    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
      return 'fas fa-share-square';
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
      return "Outputs";
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
            ID::make(__('ID'), 'id')->sortable()->onlyOnIndex(),

            Date::make('Date')
                ->rules('required')
                ->default(Carbon::now()),

            BelongsTo::make('Location')
                // ->searchable()
                ->showOnCreating(function($request){
                    if($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()){
                        return true;
                    }
                    return false;
                })->showOnUpdating(function($request){
                    if($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()){
                        return true;
                    }
                    return false;
                })
                ->showOnDetail(function($request){
                    if($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()){
                        return true;
                    }
                    return false;
                })
                ->showOnIndex(function($request){
                    if($request->user()->hasPermissionTo('view all locations data') || $request->user()->isSuperAdmin()){
                        return true;
                    }
                    return false;
                }),

            AjaxSelect::make('Category', 'category_id')
                ->rules('required')
                ->get('/locations/{location}/product-categories')
                ->parent('location')->onlyOnForms()
                ->showOnCreating(function ($request) {
                    if ($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })->showOnUpdating(function ($request) {
                    if ($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Category', 'category', 'App\Nova\ProductCategory')
                ->exceptOnForms(),

            BelongsTo::make('Category', 'category', 'App\Nova\ProductCategory')
                ->onlyOnForms()
                ->hideWhenCreating(function ($request) {
                    if ($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })->hideWhenUpdating(function ($request) {
                    if ($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            AjaxSelect::make('Style', 'style_id')
                ->rules('required')
                ->get('/locations/{location}/styles')
                ->parent('location')->onlyOnForms()
                ->showOnCreating(function ($request) {
                    if ($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })->showOnUpdating(function ($request) {
                    if ($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Style', 'style', 'App\Nova\Style')
                ->exceptOnForms(),

            BelongsTo::make('Style', 'style', 'App\Nova\Style')
                ->onlyOnForms()
                ->hideWhenCreating(function ($request) {
                    if ($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })->hideWhenUpdating(function ($request) {
                    if ($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Number::make('Quantity')
                ->rules('required', 'numeric', 'min:0'),

            Currency::make('rate')
                ->currency("BDT")
                ->exceptOnForms()
                ->hideFromIndex(),

            Currency::make('Amount')
                ->currency("BDT")
                ->exceptOnForms()
                ->hideFromIndex(),

            Trix::make('Note')
                ->rules('nullable', 'max:500'),

            AjaxSelect::make('Section', 'section_id')
                ->rules('required')
                ->get('/locations/{location}/sections')
                ->parent('location')
                ->onlyOnForms()
                ->showOnCreating(function ($request) {
                    if ($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })->showOnUpdating(function ($request) {
                    if ($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Section')
                ->onlyOnForms()
                ->hideWhenCreating(function ($request) {
                    if ($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })->hideWhenUpdating(function ($request) {
                    if ($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),


            BelongsTo::make('Section')
                ->exceptOnForms(),

            AjaxSelect::make('Sub Section', 'sub_section_id')
                ->rules('required')
                ->get('/sections/{section_id}/sub-sections')
                ->parent('section_id')
                ->onlyOnForms()
                ->showOnCreating(function ($request) {
                    if ($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })->showOnUpdating(function ($request) {
                    if ($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Sub Section', 'subSection')
                ->onlyOnForms()
                ->hideWhenCreating(function ($request) {
                    if ($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })->hideWhenUpdating(function ($request) {
                    if ($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),


            BelongsTo::make('Sub Section', 'subSection')
                ->exceptOnForms(),

            Badge::make('Status')->map([
                    OutputStatus::DRAFT()->getValue()        => 'warning',
                    OutputStatus::CONFIRMED()->getValue()    => 'info',
                    OutputStatus::ADD_TO_STOCK()->getValue() => 'success',
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
        return [];
    }
}
