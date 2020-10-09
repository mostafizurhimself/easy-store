<?php

namespace App\Nova;

use Laravel\Nova\Nova;
use Eminiarts\Tabs\Tabs;
use App\Models\Permission;
use Laravel\Nova\Resource;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Select;
use App\Models\Role as RoleModel;
use Laravel\Nova\Fields\MorphToMany;
use Benjaminhirsch\NovaSlugField\Slug;
use Eminiarts\NovaPermissions\Checkboxes;
use Laravel\Nova\Http\Requests\NovaRequest;
use Benjaminhirsch\NovaSlugField\TextWithSlug;
use ChrisWare\NovaBreadcrumbs\Traits\Breadcrumbs;
use Easystore\PermissionCheckbox\PermissionCheckbox;

class Role extends Resource
{
    use TabsOnEdit, Breadcrumbs;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = RoleModel::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can attach'];

    /**
     * The group associated with the resource.
     *
     * @var string
     */
    public static $group = 'ACL Section';

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 2;

    /**
     * Get the human readable name of the resource
     *
     * @return string
     */
    public static function readableName()
    {
        return str_replace('-', ' ', static::uriKey());
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'display_name', 'name'
    ];

    /**
     * Default ordering for index query.
     *
     * @var array
     */
    public static $sort = [
        'id' => 'asc'
    ];

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->display_name;
    }

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        return "Users: " . count($this->users);
    }


    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-id-card';
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        $guardOptions = collect(config('auth.guards'))->mapWithKeys(function ($value, $key) {
            return [$key => $key];
        });

        $userResource = Nova::resourceForModel(getModelForGuard($this->guard_name));

        return [

            ID::make('Id', 'id')
                ->rules('required')
                ->hideFromIndex()
                ->hideFromDetail(),

            TextWithSlug::make('Display Name')
                ->slug('name')
                ->rules(['required', 'string', 'max:255', 'alpha_space', 'multi_space'])
                ->creationRules('unique:' . config('permission.table_names.roles'))
                ->updateRules('unique:' . config('permission.table_names.roles') . ',display_name,{{resourceId}}'),

            Text::make('Name')
                ->onlyOnDetail(),

            Slug::make('Name')
                ->rules(['required', 'string', 'max:255'])
                ->onlyOnForms(),


            Select::make(__('Guard Name'), 'guard_name')
                ->options($guardOptions->toArray())
                ->rules(['required', Rule::in($guardOptions)])
                ->readonly(function ($request) {
                    return !$request->user()->isSuperAdmin();
                }),

            //Permissions for super-admin
            PermissionCheckbox::make(__('Permissions'), 'prepared_permissions')
                ->withGroups()
                ->options(Permission::whereIn('id', request()->user()->getAllPermissions()->pluck('id'))
                ->get()->sortBy('group_order')->map(function ($permission, $key) {
                    return [
                        'group'  => __(Str::title($permission->group)),
                        'option' => $permission->name,
                        'label'  => __(Str::title($permission->name)),
                    ];
                })
                    ->groupBy('group')->toArray())
                    ->canSee(function ($request) {
                        return $request->user()->hasPermissionTo('assign permissions') || $request->user()->isSuperAdmin();
                    }),

            Text::make(__('Users'), function () {
                return count($this->users);
            })
                ->exceptOnForms(),

            MorphToMany::make($userResource::label(), 'users', $userResource),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    public static function singularLabel()
    {
        return __('Role');
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

            return $query->orderBy(key(static::$sort), reset(static::$sort));
        }

        return $query;
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
    public static function relatableRoles(NovaRequest $request, $query)
    {
      return $query->where('name' != RoleModel::SUPER_ADMIN);
    }
}
