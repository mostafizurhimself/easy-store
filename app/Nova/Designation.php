<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use NovaAjaxSelect\AjaxSelect;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\TextUppercase\TextUppercase;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;

class Designation extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Designation';

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">03</span>Organization';

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 5;

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-id-card-alt';
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @return string
     */
    public function title()
    {
        return $this->name;
    }

    /**
     * Get the breadcrumb resource title
     *
     * @return string
     */
     public function breadcrumbResourceTitle()
     {
         return $this->name;
     }



    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        return "Location: {$this->location->name}";
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'location'   => ['name'],
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
            ID::make()->sortable()->onlyOnIndex(),

            BelongsTo::make('Location')
                ->searchable()
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
                })
                ->showOnDetail(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })
                ->showOnIndex(function ($request) {
                    if ($request->user()->hasPermissionTo('view all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'string', 'max:45', 'alpha_space', 'multi_space')
                ->creationRules([
                    Rule::unique('designations', 'name')->where('location_id', request()->get('location') ?? request()->user()->locationId)
                ])
                ->updateRules([
                    Rule::unique('designations', 'name')->where('location_id', request()->get('location') ?? request()->user()->locationId)
                        ->ignore($this->resource->id)
                ])
                ->fillUsing(function($request, $model){
                    $model['name'] = Str::title($request->name);
                })
                ->help('Your input will be converted to title case. Exp: "title case" to "Title Case".'),

            TextUppercase::make('Code')
                ->sortable()
                ->rules('required', 'string', 'max:3', 'space', 'alpha_num')
                ->creationRules([
                    Rule::unique('designations', 'name')->where('location_id', request()->get('location') ?? request()->user()->locationId)
                ])
                ->updateRules([
                    Rule::unique('designations', 'name')->where('location_id', request()->get('location') ?? request()->user()->locationId)
                        ->ignore($this->resource->id)
                ]),
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
            (new LocationFilter)->canSee(function($request){
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
            }),
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
        return '/resources/'.static::uriKey();
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
        return '/resources/'.static::uriKey();
    }
}
