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
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Lenses\Material\ItSections;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Benjacho\BelongsToManyField\BelongsToManyField;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\Materials\UpdateOpeningQuantity;

class Material extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Material';

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    {
        $model = static::$model;
        $model = new $model;
        $model->status = ActiveStatus::ACTIVE();
        return $model;
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @return string
     */
    public function title()
    {
        return "{$this->name} ({$this->code})";
    }


    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        $subtitle = "Code: " . $this->code;
        $subtitle .= " Location: " . $this->location->name;

        return $subtitle;
    }

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">05</span>Material Section';

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 2;

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-boxes';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'code',
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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable()->onlyOnIndex(),

            BelongsTo::make('Location')
                // ->searchable()
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

            Text::make('Name')
                ->sortable()
                ->rules('required', 'string', 'max:100')
                ->creationRules([
                    Rule::unique('materials', 'name')->where('location_id', request()->get('location') ?? request()->user()->locationId)
                ])
                ->updateRules([
                    Rule::unique('materials', 'name')->where('location_id', request()->get('location') ?? request()->user()->locationId)->ignore($this->resource->id)
                ]),

            Text::make('Code')
                ->sortable()
                ->help('If you want to generate code automatically, leave the field blank.')
                ->rules('nullable', 'string', 'max:100')
                ->creationRules([
                    Rule::unique('materials', 'code')->where('location_id', request()->get('location') ?? request()->user()->locationId)
                ])
                ->updateRules([
                    Rule::unique('materials', 'code')->where('location_id', request()->get('location') ?? request()->user()->locationId)->ignore($this->resource->id)
                ]),

            Images::make('Image', 'material-images')
                ->croppable(true)
                ->singleImageRules('max:5000', 'mimes:jpg,jpeg,png')
                ->hideFromIndex(),

            Trix::make('Description')
                ->rules('nullable', 'max:500'),

            Currency::make('Rate')
                ->currency('BDT')
                ->rules('required', 'numeric', 'min:0'),

            Number::make('Opening Quantity')
                ->rules('required', 'numeric', 'min:0')
                ->hideWhenUpdating()
                ->hideFromDetail()
                ->hideFromIndex(),

            Text::make('Opening Quantity')
                ->displayUsing(function () {
                    return $this->openingQuantity . " " . $this->unit->name;
                })
                ->onlyOnDetail(),

            Number::make('Alert Quantity')
                ->onlyOnForms()
                ->rules('required', 'numeric', 'min:0')
                ->hideFromIndex(),

            Text::make('Alert Quantity')
                ->displayUsing(function () {
                    return $this->alertQuantity . " " . $this->unit->name;
                })
                ->onlyOnDetail(),

            Text::make('Quantity')
                ->displayUsing(function () {
                    return $this->quantity . " " . $this->unit->name;
                })
                ->exceptOnForms(),

            BelongsTo::make('Unit')
                ->hideFromIndex()
                ->showCreateRelationButton(),

            AjaxSelect::make('Category', 'category_id')
                ->rules('required')
                ->get('/locations/{location}/material-categories')
                ->parent('location')
                ->onlyOnForms()
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

            BelongsTo::make('Category', 'category', 'App\Nova\MaterialCategory')
                ->exceptOnForms(),

            BelongsTo::make('Category', 'category', 'App\Nova\MaterialCategory')
                ->onlyOnForms()
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
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            HasMany::make('Distributions', 'distributions', "App\Nova\MaterialDistribution"),


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
        return [
            new ItSections(),
        ];
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
                return $request->user()->hasPermissionTo('can update materials opening quantity');
            })->onlyOnDetail(),
        ];
    }
}
