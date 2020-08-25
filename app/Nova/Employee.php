<?php

namespace App\Nova;

use App\Enums\Gender;
use Eminiarts\Tabs\Tabs;
use App\Enums\BloodGroup;
use App\Models\Designation;
use Illuminate\Support\Str;
use Inspheric\Fields\Email;
use Laravel\Nova\Fields\ID;
use App\Enums\MaritalStatus;
use Illuminate\Http\Request;
use App\Enums\EmployeeStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Eminiarts\Tabs\TabsOnEdit;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Easystore\RouterLink\RouterLink;
use Bissolli\NovaPhoneField\PhoneNumber;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use Hubertnnn\LaravelNova\Fields\DynamicSelect\DynamicSelect;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;

class Employee extends Resource
{
    use TabsOnEdit, SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Employee';

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    {
            $model = static::$model;
            $var = new $model;
            $var->status= EmployeeStatus::ACTIVE()->getValue();
            return $var;
    }

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
    public static $priority = 6;

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
      return 'fas fa-user-tag';
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title(){
        return $this->readableId;
    }

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        $subtitle = "Designation: ".$this->designation->name;
        $subtitle .= "; Location: ".$this->location->name;
        return $subtitle;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'readable_id', 'first_name', 'last_name', 'mobile'
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'location' => ['name'],
        'designation' => ['name'],
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
            (new Tabs('Employee Details', [
                'Profile' => [
                    RouterLink::make('Employee Id', 'id')
                        ->withMeta([
                            'label' => $this->readableId,
                        ]),

                    Text::make('First Name')
                        ->rules('required', 'string', 'max:50'),

                    Text::make('Last Name')
                        ->rules('required', 'string', 'max:50'),

                    Email::make('Personal Email')
                        ->alwaysClickable()
                        ->sortable()
                        ->hideFromIndex(),

                    PhoneNumber::make('Mobile')
                        ->withCustomFormats('+88 ### #### ####')
                        ->onlyCustomFormats()
                        ->rules('required'),

                    PhoneNumber::make('Telephone')
                        ->withCustomFormats('## #### ####')
                        ->onlyCustomFormats()
                        ->hideFromIndex(),

                    Select::make('Status')
                        ->options(EmployeeStatus::titleCaseOptions())
                        ->rules('required')
                        ->onlyOnForms(),

                    Badge::make('Status')->map([
                            EmployeeStatus::ACTIVE()->getValue()   => 'success',
                            EmployeeStatus::VACATION()->getValue() => 'warning',
                            EmployeeStatus::INACTIVE()->getValue() => 'danger',
                            EmployeeStatus::RESIGNED()->getValue() => 'danger',
                        ])
                        ->label(function(){
                            return Str::title(Str::of($this->status)->replace('_', " "));
                        }),

                ],

                "Personal Information" => [

                    Text::make('Father Name')
                        ->rules('nullable', 'string', 'max:100')
                        ->hideFromIndex(),

                    Text::make('Mother Name')
                        ->rules('nullable', 'string', 'max:100')
                        ->hideFromIndex(),

                    Select::make('Gender')
                        ->options(Gender::titleCaseOptions())
                        ->rules('required')
                        ->onlyOnForms(),

                    Text::make('Gender')
                        ->displayUsing(function(){
                            return Str::title($this->gender);
                        })
                        ->onlyOnDetail(),


                    Select::make('Marital Status')
                        ->options(MaritalStatus::titleCaseOptions())
                        ->onlyOnForms(),

                    Text::make('Marital Status')
                        ->displayUsing(function(){
                            return Str::title($this->marital_status);
                        })
                        ->onlyOnDetail(),

                    Select::make('Blood Group')
                        ->options(BloodGroup::titleCaseOptions())
                        ->hideFromIndex(),

                    Text::make('Nationality')
                        ->rules('nullable', 'string', 'max:50')
                        ->hideFromIndex(),

                ],

                "Official Information" => [


                    NovaBelongsToDepend::make('Location')
                        ->placeholder('Choose an option') // Add this just if you want to customize the placeholder
                        ->options(\App\Models\Location::all())
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

                    BelongsTo::make('Department')
                        ->onlyOnDetail(),

                    BelongsTo::make('Section')
                        ->onlyOnDetail(),

                    NovaBelongsToDepend::make('Designation')
                        ->placeholder('Choose an option') // Add this just if you want to customize the placeholder
                        ->optionsResolve(function ($location) {
                            // Reduce the amount of unnecessary data sent
                            return $location->designations->map(function($value) {
                                $designation = Designation::find($value->id);
                                $department = $designation->department ? $designation->department->name." >> " : "";
                                $section = $designation->section ? $designation->section->name." >> " : "";
                                return [ 'id' => $designation->id, 'name' => $department . $section . $designation->name ];
                            });
                        })
                        ->onlyOnForms()
                        ->dependsOn('Location')
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

                    BelongsTo::make('Designation')
                        ->exceptOnForms(),

                    BelongsTo::make('Designation')
                        ->onlyOnForms()
                        ->nullable()
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

                    Date::make('Joining Date')
                        ->rules('required')
                        ->hideFromIndex(),

                    Date::make('Resign Date')
                        ->hideFromIndex(),

                    Currency::make('Salary')
                        ->currency('BDT')
                        ->rules('required', 'numeric', 'min:0')
                        ->hideFromIndex(),
                ]

            ]))->withToolbar(),
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
