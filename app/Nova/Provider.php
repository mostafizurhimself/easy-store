<?php

namespace App\Nova;

use App\Helpers\Money;
use Laravel\Nova\Panel;
use Eminiarts\Tabs\Tabs;
use App\Enums\ActiveStatus;
use Illuminate\Support\Str;
use Inspheric\Fields\Email;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphMany;
use Easystore\RouterLink\RouterLink;
use Laravel\Nova\Fields\BelongsToMany;
use Bissolli\NovaPhoneField\PhoneNumber;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;

class Provider extends Resource
{
    use WithOutLocation;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Provider::class;

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Vendor Section';

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = [];

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
        return "Mobile: $this->mobile";
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-user-secret';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'readable_id', 'name', 'email', 'mobile'
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
            (new Tabs("Provider Details", [
                "Provider Info" => [
                    RouterLink::make('Provider Id', 'id')
                        ->withMeta([
                            'label' => $this->readableId,
                        ])
                        ->sortable(),

                    Text::make('Name')
                        ->rules('required', 'max:45', 'multi_space')
                        ->creationRules('unique:providers,name')
                        ->updateRules('unique:providers,name,{{resourceId}}')
                        ->fillUsing(function ($request, $model) {
                            $model['name'] = Str::title($request->name);
                        })
                        ->sortable()
                        ->help('Your input will be converted to title case. Exp: "title case" to "Title Case".'),

                    PhoneNumber::make('Mobile')
                        ->withCustomFormats('+88 ### #### ####')
                        ->onlyCustomFormats()
                        ->rules('required'),

                    PhoneNumber::make('Telephone')
                        ->withCustomFormats('## #### ####')
                        ->onlyCustomFormats()
                        ->hideFromIndex(),

                    Email::make('Email')
                        ->alwaysClickable()
                        ->sortable()
                        ->rules('nullable', 'email'),

                    Text::make('Fax')
                        ->nullable()
                        ->sortable()
                        ->rules('max:200')
                        ->hideFromIndex(),

                    Text::make('Vat Number')
                        ->nullable()
                        ->sortable()
                        ->rules('max:200')
                        ->hideFromIndex(),

                    Currency::make('Opening Balance')
                        ->currency('BDT')
                        ->rules('required', 'numeric', 'min:0')
                        ->hideWhenUpdating()
                        ->sortable()
                        ->hideFromIndex(),

                    Currency::make('Balance')
                        ->currency('BDT')
                        ->sortable()
                        ->rules('required', 'numeric', 'min:0')
                        ->exceptOnForms(),

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
                "Services" => [
                    BelongsToMany::make('Services')
                ]
            ]))->withToolbar(),


            MorphMany::make('Address'),
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
            //
        ];
    }
}
