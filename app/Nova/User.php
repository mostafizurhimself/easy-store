<?php

namespace App\Nova;

use Eminiarts\Tabs\Tabs;
use App\Models\Permission;
use Inspheric\Fields\Email;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Eminiarts\Tabs\TabsOnEdit;
use Laravel\Nova\Fields\Boolean;
use Yassi\NestedForm\NestedForm;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphToMany;
use App\Nova\Actions\Users\MakeAsActive;
use Eminiarts\NovaPermissions\Checkboxes;
use App\Nova\Actions\Users\MakeAsInactive;
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
    public static $model = 'App\\Models\\User';

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    {
            $model = static::$model;
            $var = new $model;
            $var->active= true;
            return $var;
    }

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
                    // ID::make()->sortable()->onlyOnIndex(),

                    Images::make('Avatar', 'avatar')
                        ->onlyOnIndex(),

                    Text::make('Name')
                        ->sortable()
                        ->rules('required', 'max:255'),

                    BelongsTo::make('Location')
                        ->searchable(),

                    Email::make('Email')
                        ->alwaysClickable()
                        ->sortable()
                        ->rules('required', 'email', 'max:254')
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

                    Boolean::make('Active'),
                ],
                'Permissions' => [
                      //Permissions for super-admin
                        PermissionCheckbox::make(__('Permissions'), 'prepared_permissions')
                                        ->withGroups()
                                        ->options(Permission::all()->sortBy('group_order')->map(function ($permission, $key) {
                                                return [
                                                    'group'  => __(Str::title($permission->group)),
                                                    'option' => $permission->name,
                                                    'label'  => __(Str::title($permission->name)),
                                                ];
                                        })
                                        ->groupBy('group')
                                        ->toArray())
                                        ->canSee(function($request){
                                            return $request->user()->isSuperAdmin();
                                        })
                                        ->hideFromIndex(),

                        //Permission for other users.
                        PermissionCheckbox::make(__('Permissions'), 'prepared_permissions')
                                        ->withGroups()
                                        ->options(Permission::where('group', '!=', Permission::SUPER_ADMIN_GROUP)->get()->sortBy('group_order')->map(function ($permission, $key) {
                                                return [
                                                    'group'  => __(Str::title($permission->group)),
                                                    'option' => $permission->name,
                                                    'label'  => __(Str::title($permission->name)),
                                                ];
                                        })->groupBy('group')->toArray())
                                        ->canSee(function($request){
                                            return $request->user()->hasPermissionTo('assign permissions') && !$request->user()->isSuperAdmin();
                                        })
                                        ->hideFromIndex(),
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
            (new MakeAsActive)->canSee(function($request){
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('update users');
            })->onlyOnIndex(),
            (new MakeAsInactive)->canSee(function($request){
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('update users');
            })->onlyOnIndex(),
        ];
    }

}
