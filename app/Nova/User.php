<?php

namespace App\Nova;

use Eminiarts\Tabs\Tabs;
use App\Models\Permission;
use App\Enums\ActiveStatus;
use Illuminate\Support\Str;
use Inspheric\Fields\Email;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Eminiarts\Tabs\TabsOnEdit;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Laravel\Nova\Fields\MorphToMany;
use App\Nova\Actions\Users\MarkAsActive;
use App\Nova\Actions\Users\MarkAsInactive;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\PasswordConfirmation;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Easystore\PermissionCheckbox\PermissionCheckbox;

class User extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can attach', 'can mark as active', 'can mark as inactive'];

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
    public static $priority = 1;

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-users';
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
        return "Email: {$this->email}";
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'email',
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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [

            (new Tabs('User Details', [
                'User Info'    => [
                    ID::make()->sortable()->onlyOnIndex(),

                    Images::make('Avatar', 'avatar')
                        ->onlyOnIndex(),

                    Text::make('Name')
                        ->sortable()
                        ->rules('required', 'max:50', 'multi_space')
                        ->fillUsing(function ($request, $model) {
                            $model['name'] = Str::title($request->name);
                        })
                        ->sortable()
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

                    Email::make('Email')
                        ->alwaysClickable()
                        ->sortable()
                        ->rules('required', 'email', 'max:50')
                        ->creationRules('unique:users,email')
                        ->updateRules('unique:users,email,{{resourceId}}'),

                    Password::make('Password')
                        ->onlyOnForms()
                        ->creationRules('required', 'string', 'min:4', 'max:20', 'confirmed')
                        ->updateRules('nullable', 'string', 'min:4', 'max:20', 'confirmed'),

                    PasswordConfirmation::make('Password Confirmation'),

                    Images::make('Profile Picture', 'avatar') // second parameter is the media collection name
                        ->croppable(true)
                        ->hideFromIndex()
                        ->singleImageRules('max:5000', 'mimes:jpg,jpeg,png'),

                    Select::make('Status')
                        ->options(ActiveStatus::titleCaseOptions())
                        ->default(ActiveStatus::ACTIVE())
                        ->rules('required')
                        ->onlyOnForms(),


                    Badge::make('Status')->map([
                        ActiveStatus::ACTIVE()->getValue()   => 'success',
                        ActiveStatus::INACTIVE()->getValue() => 'danger',
                    ])
                        ->sortable()
                        ->label(function () {
                            return Str::title(Str::of($this->status)->replace('_', " "));
                        }),
                ],
                'Permissions' => [
                    //Permissions for super-admin
                    PermissionCheckbox::make(__('Permissions'), 'prepared_permissions')
                        ->withGroups()
                        ->options(Permission::whereIn('id', request()->user()->getAllPermissions()->pluck('id'))
                            ->show()
                            ->get()->sortBy('group_order')->map(function ($permission, $key) {
                                return [
                                    'group'  => __(Str::title($permission->group)),
                                    'option' => $permission->name,
                                    'label'  => __(Str::title($permission->name)),
                                ];
                            })
                            ->groupBy('group')
                            ->toArray())
                        ->hideFromIndex()
                        ->canSee(function ($request) {
                            return $request->user()->hasPermissionTo('assign permissions') || $request->user()->isSuperAdmin();
                        }),
                ],
            ]))->withToolbar(),

            MorphOne::make('Address'),
            // NestedForm::make('Address'),

            MorphToMany::make('Roles', 'roles', Role::class),
            // MorphToMany::make('Permissions', 'permissions', \Eminiarts\NovaPermissions\Nova\Permission::class),
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
            LocationFilter::make('Location', 'location_id')->canSee(function ($request) {
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
        return [
            (new MarkAsActive)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can mark as active users');
            }),
            (new MarkAsInactive)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can mark as inactive users');
            }),
        ];
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
        return $query->whereNotIn('name', [\App\Models\Role::SUPER_ADMIN, \App\Models\Role::SYSTEM_ADMIN, \App\Models\Role::EXPENSER]);
    }
}