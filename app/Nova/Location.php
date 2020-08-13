<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Inspheric\Fields\Email;
use App\Enums\LocationType;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use App\Enums\LocationStatus;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Select;
use App\Traits\WithOutLocation;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\BelongsTo;
use Easystore\RouterLink\RouterLink;
use Bissolli\NovaPhoneField\PhoneNumber;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Location extends Resource
{
    use WithOutLocation;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Location';

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    {
            $model = static::$model;
            $model = new $model;
            $model->status= LocationStatus::ACTIVE();
            return $model;
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Get the search result subtitle for the resource.
     *2
     * @return string
     */
    public function subtitle()
    {
      return "ID: {$this->readableId}";
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'readable_id', 'name'
    ];

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
    public static $priority = 1;

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
      return 'fas fa-map-marked-alt';
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

            RouterLink::make('Location Id', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ]),

            Text::make('Name')
                ->rules('required', 'max:45')
                ->creationRules('unique:locations,name')
                ->updateRules('unique:locations,name,{{resourceId}}'),

            Select::make('Type')
                ->options(LocationType::titleCaseOptions())
                ->rules('required')
                ->onlyOnForms(),

            Text::make('Type')
                ->displayUsing(function(){
                    return Str::title(Str::of($this->type)->replace('_', " "));
                })
                ->exceptOnForms(),

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
                ->hideFromIndex()
                ->rules('nullable', 'email')
                ->creationRules('unique:locations,email')
                ->updateRules('unique:locations,email,{{resourceId}}'),

            Select::make('Status')
                ->options(LocationStatus::titleCaseOptions())
                ->rules('required')
                ->onlyOnForms(),

            Badge::make('Status')->map([
                    LocationStatus::ACTIVE()->getValue()             => 'success',
                    LocationStatus::UNDER_CONSTRUCTION()->getValue() => 'warning',
                    LocationStatus::ABANDONED()->getValue()          => 'danger',
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
        return [];
    }
}
