<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use NovaAjaxSelect\AjaxSelect;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use App\Nova\Filters\DepartmentFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;

class Section extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Section';

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Organization';

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 3;

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-th-large';
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

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
        'name'
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'location' => ['name'],
        'department' => ['name'],
        'floor' => ['name'],
        'employee' => ['readable_id'],
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

            Text::make('Name')
                ->sortable()
                ->rules('required', 'string', 'max:45', 'alpha_num_space', 'multi_space')
                ->creationRules([
                    Rule::unique('sections', 'name')->where('department_id', request()->get('department') ?? request()->get('department_id'))
                ])
                ->updateRules([
                    Rule::unique('sections', 'name')->where('department_id', request()->get('department') ?? request()->get('department_id'))->ignore($this->resource->id)
                ])
                ->help('Your input will be converted to title case. Exp: "title case" to "Title Case".'),

            BelongsTo::make('Location')
                ->searchable()
                ->sortable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            AjaxSelect::make('Department', 'department_id')
                ->rules('required')
                ->get('/locations/{location}/departments')
                ->parent('location')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),


            BelongsTo::make('Department')
                ->sortable()
                ->exceptOnForms(),

            BelongsTo::make('Department')
                ->onlyOnForms()
                ->sortable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return false;
                    }
                    return true;
                }),


            AjaxSelect::make('Floor', 'floor_id')
                ->rules('required')
                ->get('/locations/{location}/floors')
                ->parent('location')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Floor')
                ->onlyOnForms()
                ->showCreateRelationButton()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return false;
                    }
                    return true;
                }),


            BelongsTo::make('Floor')
                ->sortable()
                ->exceptOnForms(),


            AjaxSelect::make('Section Incharge', 'employee_id')
                ->rules('nullable')
                ->get('/locations/{location}/employees')
                ->parent('location')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Section Incharge', 'employee', 'App\Nova\Employee')
                ->sortable()
                ->exceptOnForms(),

            BelongsTo::make('Section Incharge', 'employee', 'App\Nova\Employee')->searchable()
                ->onlyOnForms()
                ->nullable()
                ->sortable()
                ->canSee(function ($request) {
                    if (!($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data'))) {
                        return true;
                    }
                    return false;
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
            (new LocationFilter)->canSee(function ($request) {
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
            }),
            (new DepartmentFilter)->canSee(function ($request) {
                return $request->user()->locationId;
            })
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
        return '/resources/' . static::uriKey();
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
        return '/resources/' . static::uriKey();
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

        if ($request->user()->locationId && !$request->user()->hasPermissionTo('view any locations data')) {
            $query->where('location_id', $request->user()->location_id);
        }

        return $query->with('location', 'department', 'floor');
    }
}