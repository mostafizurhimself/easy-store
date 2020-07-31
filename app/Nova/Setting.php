<?php

namespace App\Nova;

use R64\NovaFields\JSON;
use Inspheric\Fields\Email;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Easystore\RouterLink\RouterLink;
use App\Models\Setting as SettingModel;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;

class Setting extends Resource
{
    use HasDependencies;
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
            RouterLink::make('Name', 'id')
                ->withMeta([
                    'label' => $this->name,
                ]),

            Text::make('Name')
                ->onlyOnForms()
                ->readonly(),

            NovaDependencyContainer::make([

                Images::make('Logo', 'settings')
                    ->singleImageRules('max:5000', 'mimes:jpg,jpeg,png')
                    ->croppable()
                    ->hideFromIndex(),

                Json::make('Settings', [
                    Text::make('Name', 'name')
                        ->rules('nullable', 'string', 'max:100'),

                    Email::make('Email', 'email')
                        ->rules('nullable', 'email')
                ])
                ->flatten()
                ->hideFromIndex(),
            ])
            ->dependsOn('name', SettingModel::APPLICATION_SETTINGS)
            ->dependsOn('name', SettingModel::COMPANY_SETTINGS),

            NovaDependencyContainer::make([

                Json::make('Settings', [
                    Text::make('Location Prefix', 'location')
                        ->rules('nullable', 'string', 'max:4'),

                    Text::make('Employee Prefix', 'employee')
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
