<?php

namespace App\Nova;

use App\Models\Unit;
use R64\NovaFields\JSON;
use Inspheric\Fields\Email;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Textarea;
use Easystore\RouterLink\RouterLink;
use App\Models\Setting as SettingModel;
use Bissolli\NovaPhoneField\PhoneNumber;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use OptimistDigital\MultiselectField\Multiselect;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;

class Setting extends Resource
{
    use HasDependencies, WithOutLocation;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Setting';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "App Settings";
    }

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Super Admin';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = ['name'];

    /**
     * Default ordering for index query.
     *
     * @var array
     */
    public static $sort = [
        'id' => 'asc'
    ];
    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-cogs';
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
            RouterLink::make('Settings Name', 'id')
                ->withMeta([
                    'label' => $this->name,
                ]),

            Text::make('Setting Name', 'name')
                ->onlyOnForms()
                ->readonly(),

            // Application Settings
            Images::make('Logo', 'settings')
                ->singleImageRules('max:5000', 'mimes:jpg,jpeg,png')
                ->croppable()
                ->hideFromIndex()
                ->readonly(function ($request) {
                    return !$request->user()->isSuperAdmin();
                })
                ->canSee(function () {
                    return $this->resource->name == SettingModel::APPLICATION_SETTINGS;
                }),

            Text::make('App Name')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->name ?? null;
                })
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::APPLICATION_SETTINGS;
                }),

            Email::make('Email')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->email ?? null;
                })
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::APPLICATION_SETTINGS;
                }),

            PhoneNumber::make('Mobile')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->mobile ?? null;
                })
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::APPLICATION_SETTINGS;
                }),

            Text::make('Approvers')
                ->displayUsing(function () {
                    $value = '';

                    if (!empty(json_decode($this->resource->settings)->approvers)) {
                        foreach (json_decode($this->resource->settings)->approvers as $approver) {
                            $employee = \App\Models\Employee::find($approver);
                            $value .= "<p class='pb-4'>{$employee->name}({$employee->readableId})</p>";
                        }
                        return $value;
                    } else {
                        return null;
                    }
                })
                ->asHtml()
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::APPLICATION_SETTINGS;
                }),

            Boolean::make('Super Admin Notification', 'super_admin_notification')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->super_admin_notification ?? null;
                })
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::APPLICATION_SETTINGS;
                }),

            Text::make('Output Unit')
                ->displayUsing(function () {

                    if (!empty(json_decode($this->resource->settings)->output_unit)) {
                        return Unit::find(json_decode($this->resource->settings)->output_unit)->name;
                    }
                })
                ->asHtml()
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::APPLICATION_SETTINGS;
                }),

            Text::make('Maximum Invoice Item')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->max_invoice_item ?? null;
                })
                ->asHtml()
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::APPLICATION_SETTINGS;
                }),

            Heading::make('Module Settings')
                ->canSee(function ($request) {
                    return $request->user()->isSystemAdmin();
                }),

            Boolean::make('Enable Product Module', 'enable_product_module')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->enable_product_module ?? null;
                })
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $this->resource->name == SettingModel::APPLICATION_SETTINGS && $request->user()->isSystemAdmin();
                }),


            NovaDependencyContainer::make([

                Json::make('Settings', [
                    Text::make('App Name', 'name')
                        ->readonly(function ($request) {
                            return !$request->user()->isSuperAdmin();
                        })
                        ->rules('nullable', 'string', 'max:100'),

                    Email::make('Email', 'email')
                        ->readonly(function ($request) {
                            return !$request->user()->isSuperAdmin();
                        })
                        ->rules('nullable', 'email'),

                    PhoneNumber::make('Mobile', 'mobile')
                        ->readonly(function ($request) {
                            return !$request->user()->isSuperAdmin();
                        })
                        ->withCustomFormats('+88 ### #### ####')
                        ->onlyCustomFormats()
                        ->rules('nullable'),

                    Multiselect::make('Approvers', 'approvers')
                        ->options(\App\Models\Employee::toSelectOptions())
                        ->placeholder('Choose options') // Placeholder text
                        ->max(10) // Maximum number of items the user can choose
                        ->saveAsJSON() // Saves value as JSON if the database column is of JSON type
                        ->optionsLimit(5) // How many items to display at once
                        ->reorderable(), // Allows reordering functionality

                    Boolean::make('Super Admin Notification', 'super_admin_notification')
                        ->readonly(function ($request) {
                            return !$request->user()->isSuperAdmin();
                        }),

                    Select::make("Output Unit", 'output_unit')
                        ->rules('required')
                        ->options(Unit::all()->pluck('name', 'id')),

                    Number::make('Maximum Invoice Item', 'max_invoice_item')
                        ->rules('required', 'numeric', 'min:3', 'max:50'),

                    Heading::make('Module Settings')
                        ->canSee(function ($request) {
                            return $request->user()->isSystemAdmin();
                        }),

                    Boolean::make('Enable Product Module', 'enable_product_module')
                        ->canSee(function ($request) {
                            return $request->user()->isSystemAdmin();
                        }),


                ])
                    ->flatten(),
            ])
                ->dependsOn('name', SettingModel::APPLICATION_SETTINGS),

            // Company Settings

            Images::make('Logo', 'settings')
                ->singleImageRules('max:5000', 'mimes:jpg,jpeg,png')
                ->croppable()
                ->hideFromIndex()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::COMPANY_SETTINGS;
                }),

            Text::make('Company Name')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->name ?? null;
                })
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::COMPANY_SETTINGS;
                }),

            Email::make('Email')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->email ?? null;
                })
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::COMPANY_SETTINGS;
                }),

            PhoneNumber::make('Mobile')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->mobile ?? null;
                })
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::COMPANY_SETTINGS;
                }),

            Textarea::make('Address')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->address ?? null;
                })
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::COMPANY_SETTINGS;
                }),

            NovaDependencyContainer::make([

                Json::make('Settings', [
                    Text::make('Company Name', 'name')
                        ->rules('nullable', 'string', 'max:100'),

                    Email::make('Email', 'email')
                        ->rules('nullable', 'email'),

                    PhoneNumber::make('Mobile', 'mobile')
                        ->withCustomFormats('+88 ### #### ####')
                        ->onlyCustomFormats()
                        ->rules('nullable'),

                    Textarea::make('Address', 'address')
                        ->rules('nullable'),

                ])
                    ->flatten(),
            ])
                ->dependsOn('name', SettingModel::COMPANY_SETTINGS),


            // Prefix Settings
            Text::make('Location Prefix', 'location')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->location ?? null;
                })
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::PREFIX_SETTINGS;
                }),

            Text::make('Supplier Prefix', 'supplier')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->supplier ?? null;
                })
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::PREFIX_SETTINGS;
                }),

            Text::make('Provider Prefix', 'provider')
                ->displayUsing(function () {
                    return json_decode($this->resource->settings)->provider ?? null;
                })
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->resource->name == SettingModel::PREFIX_SETTINGS;
                }),
            NovaDependencyContainer::make([

                Json::make('Settings', [
                    Text::make('Location Prefix', 'location')
                        ->rules('nullable', 'string', 'max:4'),

                    Text::make('Supplier Prefix', 'supplier')
                        ->rules('nullable', 'string', 'max:4'),

                    Text::make('Provider Prefix', 'provider')
                        ->rules('nullable', 'string', 'max:4'),
                ])
                    ->flatten()
                    ->hideFromIndex(),
            ])
                ->dependsOn('name', SettingModel::PREFIX_SETTINGS),
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
