<?php

namespace App\Nova;

use App\Enums\ActiveStatus;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use App\Nova\Actions\Assets\UpdateOpeningQuantity;
use Benjacho\BelongsToManyField\BelongsToManyField;
use Titasgailius\SearchRelations\SearchesRelations;

class Asset extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Asset';

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    {
            $model = static::$model;
            $model = new $model;
            $model->status= ActiveStatus::ACTIVE();
            return $model;
    }

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">06</span>Asset Section';

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
        $subtitle = "Code: ".$this->code;
        $subtitle.= " Location: ".$this->location->name;

        return $subtitle;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'code'
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'location' => ['name'],
        'category' => ['name'],
    ];


    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
      return 'fas fa-box-open';
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
            ID::make()->sortable(),

            BelongsTo::make('Location')
                ->searchable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'string', 'max:100')
                ->creationRules([
                    Rule::unique('assets', 'name')->where('location_id', request()->get('location'))
                ])
                ->updateRules([
                    Rule::unique('assets', 'name')->where('location_id', request()->get('location'))->ignore($this->resource->id)
                ]),

            BelongsTo::make('Category', 'category', 'App\Nova\AssetCategory')
                ->onlyOnIndex(),

            Text::make('Code')
                ->sortable()
                ->help('If you want to generate code automatically, leave the field blank.')
                ->rules('nullable', 'string', 'max:100')
                ->creationRules([
                    Rule::unique('assets', 'code')->where('location_id', request()->get('location'))
                ])
                ->updateRules([
                    Rule::unique('assets', 'code')->where('location_id', request()->get('location'))->ignore($this->resource->id)
                ]),

            Images::make('Image', 'material-image')
                ->croppable(true)
                ->singleImageRules('max:5000', 'mimes:jpg,jpeg,png')
                ->hideFromIndex(),

            Trix::make('Description')
                ->nullable()
                ->rules('max:500'),

            Currency::make('Rate')
                ->currency('BDT')
                ->rules('required', 'numeric', 'min:0'),

            Number::make('Opening Quantity')
                ->rules('required', 'numeric', 'min:0')
                ->hideWhenUpdating()
                ->hideFromDetail()
                ->hideFromIndex(),

            Text::make('Opening Quantity')
                ->displayUsing(function(){
                    return $this->openingQuantity ." ".$this->unit->name;
                })
                ->onlyOnDetail(),

            Number::make('Alert Quantity')
                ->onlyOnForms()
                ->rules('required', 'numeric', 'min:0')
                ->hideFromIndex(),

            Text::make('Alert Quantity')
                ->displayUsing(function(){
                    return $this->alertQuantity ." ".$this->unit->name;
                })
                ->onlyOnDetail(),

            Text::make('Quantity')
                ->displayUsing(function(){
                    return $this->quantity ." ".$this->unit->name;
                })
                ->exceptOnForms(),

            BelongsTo::make('Unit')
                ->hideFromIndex()
                ->showCreateRelationButton(),

            AjaxSelect::make('Category', 'category_id')
                ->rules('required')
                ->get('/locations/{location}/asset-categories')
                ->parent('location'),

            BelongsToManyField::make('Suppliers', 'suppliers', 'App\Nova\Supplier')
                ->hideFromIndex(),

            Select::make('Status')
                ->options(ActiveStatus::titleCaseOptions())
                ->rules('required')
                ->onlyOnForms(),

            Badge::make('Status')->map([
                    ActiveStatus::ACTIVE()->getValue()   => 'success',
                    ActiveStatus::INACTIVE()->getValue() => 'danger',
                ])
                ->label(function(){
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

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
            (new UpdateOpeningQuantity)->canSee(function($request){
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('update assets');
            })->onlyOnDetail(),
        ];
    }
}
