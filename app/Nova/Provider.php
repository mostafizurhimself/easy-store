<?php

namespace App\Nova;

use App\Helpers\Money;
use Laravel\Nova\Panel;
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
use Laravel\Nova\Fields\MorphMany;
use Easystore\RouterLink\RouterLink;
use Bissolli\NovaPhoneField\PhoneNumber;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use App\Nova\Actions\Providers\UpdateOpeningBalance;

class Provider extends Resource
{
    use WithOutLocation;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Provider';

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">10</span>Vendor Section';

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
            RouterLink::make('Provider Id', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ]),

            Text::make('Name')
                ->rules('required', 'max:45', 'alpha_space', 'multi_space')
                ->creationRules('unique:suppliers,name')
                ->updateRules('unique:suppliers,name,{{resourceId}}')
                ->fillUsing(function($request, $model){
                    $model['name'] = Str::title($request->name);
                })
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
                ->rules('nullable', 'email'),

            Text::make('Fax')
                ->nullable()
                ->rules('string', 'max:200')
                ->hideFromIndex(),

            Text::make('Vat Number')
                ->nullable()
                ->rules('string', 'max:200')
                ->hideFromIndex(),

            Currency::make('Opening Balance')
                ->currency('BDT')
                ->rules('required', 'numeric', 'min:0')
                ->hideWhenUpdating()
                ->hideFromIndex(),

            Currency::make('Balance')
                ->currency('BDT')
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
                ->label(function(){
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

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
            (new UpdateOpeningBalance)->canSee(function($request){
                return $request->user()->hasPermissionTo('can update products opening balance');
            })->onlyOnDetail(),
        ];
    }
}
