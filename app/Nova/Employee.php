<?php

namespace App\Nova;

use Carbon\Carbon;
use App\Enums\Gender;
use Eminiarts\Tabs\Tabs;
use App\Enums\BloodGroup;
use App\Models\Department;
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
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphMany;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\Filters\DepartmentFilter;
use App\Nova\Actions\Employees\EisForm;
use AwesomeNova\Filters\DependentFilter;
use Bissolli\NovaPhoneField\PhoneNumber;
use App\Nova\Actions\Employees\DownloadPdf;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\Employees\DownloadExcel;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
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
    public static $model = \App\Models\Employee::class;

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
    public function title()
    {
        return "{$this->name} ({$this->readableId})";
    }

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        $subtitle = "Designation: " . $this->designation->name;
        $subtitle .= "; Location: " . $this->location->name;
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
                        ])
                        ->sortable(),

                    Text::make('Name')
                        ->exceptOnForms()
                        ->displayUsing(function () {
                            return $this->name;
                        })
                        ->sortable(),

                    Text::make('First Name')
                        ->rules('required', 'string', 'max:50')
                        ->onlyOnForms()
                        ->sortable(),

                    Text::make('Last Name')
                        ->rules('required', 'string', 'max:50')
                        ->onlyOnForms()
                        ->sortable(),

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

                    Images::make('Image', 'employee-images')
                        ->croppable(true)
                        ->hideFromIndex()
                        ->singleImageRules('max:5000', 'mimes:jpg,jpeg,png'),

                    Select::make('Status')
                        ->options(EmployeeStatus::titleCaseOptions())
                        ->rules('required')
                        ->default(EmployeeStatus::ACTIVE())
                        ->onlyOnForms(),

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
                        ->displayUsing(function () {
                            return Str::title($this->gender);
                        })
                        ->onlyOnDetail(),


                    Select::make('Marital Status')
                        ->options(MaritalStatus::titleCaseOptions())
                        ->onlyOnForms(),

                    Text::make('Marital Status')
                        ->displayUsing(function () {
                            return Str::title($this->marital_status);
                        })
                        ->onlyOnDetail(),

                    Select::make('Blood Group')
                        ->options(BloodGroup::titleCaseOptions())
                        ->hideFromIndex(),

                    Text::make('Nationality')
                        ->default('Bangladeshi')
                        ->rules('nullable', 'string', 'max:50')
                        ->hideFromIndex(),

                    Files::make('Attachments', 'employee-attachments')
                        ->hideFromIndex(),

                ],

                "Official Information" => [


                    BelongsTo::make('Location')
                        ->searchable()
                        ->canSee(function ($request) {
                            if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                                return true;
                            }
                            return false;
                        }),

                    BelongsTo::make('Department')
                        ->exceptOnForms(),

                    AjaxSelect::make('Department', 'department_id')
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
                        ->nullable()
                        ->onlyOnForms()
                        ->canSee(function ($request) {
                            if (!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()) {
                                return true;
                            }
                            return false;
                        }),

                    BelongsTo::make('Section')
                        ->onlyOnDetail(),

                    AjaxSelect::make('Section', 'section_id')
                        ->get('/departments/{department_id}/sections')
                        ->parent('department_id')
                        ->onlyOnForms(),

                    AjaxSelect::make('Designation', 'designation_id')
                        ->rules('required')
                        ->get('/locations/{location}/designations')
                        ->parent('location')
                        ->onlyOnForms()
                        ->canSee(function ($request) {
                            if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                                return true;
                            }
                            return false;
                        }),

                    BelongsTo::make('Designation')
                        ->exceptOnForms()
                        ->sortable(),

                    BelongsTo::make('Designation')
                        ->searchable()
                        ->onlyOnForms()
                        ->nullable()
                        ->canSee(function ($request) {
                            if (!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()) {
                                return true;
                            }
                            return false;
                        }),

                    AjaxSelect::make('Shift', 'shift_id')
                        ->rules('required')
                        ->get('/locations/{location}/shifts')
                        ->parent('location')
                        ->onlyOnForms()
                        ->canSee(function ($request) {
                            if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                                return true;
                            }
                            return false;
                        }),

                    BelongsTo::make('Shift')
                        ->onlyOnDetail()
                        ->sortable(),

                    BelongsTo::make('Shift')
                        ->searchable()
                        ->onlyOnForms()
                        ->nullable()
                        ->canSee(function ($request) {
                            if (!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()) {
                                return true;
                            }
                            return false;
                        }),

                    Date::make('Joining Date')
                        ->rules('required')
                        ->sortable()
                        ->hideFromIndex(),

                    Date::make('Resign Date')
                        ->hideFromIndex(),

                    Currency::make('Salary')
                        ->currency('BDT')
                        ->rules('required', 'numeric', 'min:0')
                        ->hideFromIndex(),

                    Badge::make('Status')->map([
                        EmployeeStatus::ACTIVE()->getValue()   => 'success',
                        EmployeeStatus::VACATION()->getValue() => 'warning',
                        EmployeeStatus::INACTIVE()->getValue() => 'danger',
                        EmployeeStatus::RESIGNED()->getValue() => 'danger',
                    ])
                        ->sortable()
                        ->label(function () {
                            return Str::title(Str::of($this->status)->replace('_', " "));
                        }),
                ]
            ]))->withToolbar(),

            (new Tabs('Other Details', [
                "Addresses" => [
                    MorphMany::make('Address'),
                ],
                "Educations" => [
                    HasMany::make('Educations')
                ]

            ])),
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
                return !$request->user()->isSuperAdmin() || !$request->user()->hasPermissionTo('view any locations data');
            }),

            DependentFilter::make('Department', 'department_id')
                ->dependentOf('location_id')
                ->withOptions(function (Request $request, $filters) {
                    return Department::where('location_id', $filters['location_id'])
                        ->orderBy('name')
                        ->pluck('name', 'id');
                })->canSee(function ($request) {
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
            (new EisForm)->onlyOnDetail()->withoutConfirmation(),

            (new DownloadPdf)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download assets') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download assets') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download pdf?"),

            (new DownloadExcel)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download assets') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download assets') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download excel?"),

        ];
    }
}