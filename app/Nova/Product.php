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
use Treestoneit\TextWrap\TextWrap;
use App\Nova\Filters\LocationFilter;
use Easystore\TextUppercase\TextUppercase;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\Products\UpdateOpeningQuantity;

class Product extends Resource
{
    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Product::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can update opening quantity of'];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">08</span>Product Section';

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 2;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
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
        $subtitle = " Location: " . $this->location->name;

        return $subtitle;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'code', 'name'
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
      return 'fas fa-box';
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
            ID::make(__('ID'), 'id')->sortable()->onlyOnIndex(),

            BelongsTo::make('Location')
                ->searchable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Text::make('Name')
                ->hideFromIndex()
                ->sortable()
                ->rules('required', 'string', 'max:100', 'multi_space')
                // ->creationRules([
                //     Rule::unique('products', 'name')->where('location_id', request()->get('location') ?? request()->user()->locationId)
                // ])
                // ->updateRules([
                //     Rule::unique('products', 'name')->where('location_id', request()->get('location') ?? request()->user()->locationId)->ignore($this->resource->id)
                // ])
                ->fillUsing(function($request, $model){
                    $model['name'] = Str::title($request->name);
                })
                ->help('Your input will be converted to title case. Exp: "title case" to "Title Case".'),

            TextWrap::make('Name')
                ->onlyOnIndex()
                ->wrapMethod('length',30),

            TextUppercase::make('Code')
                ->sortable()
                ->help('If you want to generate code automatically, leave the field blank.')
                ->rules('nullable', 'string', 'max:20', 'space', 'alpha_num')
                ->creationRules([
                    Rule::unique('products', 'code')->where('location_id', request()->get('location') ?? request()->user()->locationId)
                ])
                ->updateRules([
                    Rule::unique('products', 'code')->where('location_id', request()->get('location') ?? request()->user()->locationId)->ignore($this->resource->id)
                ]),

            Images::make('Image', 'product-images')
                ->croppable(true)
                ->singleImageRules('max:5000', 'mimes:jpg,jpeg,png')
                ->hideFromIndex(),

            Trix::make('Description')
                ->rules('nullable', 'max:500'),

            Currency::make('Cost Price')
                ->currency('BDT')
                ->rules('required', 'numeric', 'min:0'),

            Currency::make('Sale Price')
                ->currency('BDT')
                ->rules('required', 'numeric', 'min:0'),

            Number::make('Vat')
                ->rules('numeric', 'min:0')
                ->hideFromIndex()
                ->default(0.00),

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
                // ->hideWhenUpdating()
                ->showCreateRelationButton(),

            AjaxSelect::make('Category', 'category_id')
                ->rules('required')
                ->get('/locations/{location}/product-categories')
                ->parent('location')->onlyOnForms()
                 ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Category', 'category', 'App\Nova\ProductCategory')
                ->showCreateRelationButton()
                ->exceptOnForms(),

            BelongsTo::make('Category', 'category', 'App\Nova\ProductCategory')
                ->onlyOnForms()
               ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return false;
                    }
                    return true;
                }),

            Select::make('Status')
                ->options(ActiveStatus::titleCaseOptions())
                ->rules('required')
                ->onlyOnForms()
                ->default(ActiveStatus::ACTIVE()),

            Badge::make('Status')->map([
                ActiveStatus::ACTIVE()->getValue()   => 'success',
                ActiveStatus::INACTIVE()->getValue() => 'danger',
            ])
                ->label(function () {
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
        return [
            (new LocationFilter)->canSee(function($request){
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view all locations data');
            })
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
            (new UpdateOpeningQuantity)->canSee(function($request){
                return $request->user()->hasPermissionTo('can update opening quantity of products');
            })->onlyOnDetail(),
        ];
    }
}
