<?php

namespace App\Nova;

use Eminiarts\Tabs\Tabs;
use App\Enums\ActiveStatus;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use App\Models\FabricCategory;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use App\Nova\Actions\ConvertUnit;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphMany;
use Treestoneit\TextWrap\TextWrap;
use App\Nova\Actions\AdjustQuantity;
use App\Nova\Filters\CategoryFilter;
use App\Nova\Filters\LocationFilter;
use App\Nova\Filters\ActiveStatusFilter;
use App\Nova\Lenses\Fabric\StockSummary;
use AwesomeNova\Filters\DependentFilter;
use App\Nova\Actions\Fabrics\DownloadPdf;
use Easystore\TextUppercase\TextUppercase;
use App\Nova\Actions\Fabrics\DownloadExcel;
use App\Nova\Filters\FabricCategoryFilter;
use App\Nova\Lenses\Fabric\AlertQuantities;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Benjacho\BelongsToManyField\BelongsToManyField;
use Titasgailius\SearchRelations\SearchesRelations;

class Fabric extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Fabric::class;

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 2;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can download', 'can convert unit of', 'can adjust quantity of', 'can view stock summary of'];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Fabrics Section';

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
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-scroll';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'code', 'readable_id', 'id'
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
     * Get the fieldsdisplayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            (new Tabs("Fabric Details", [
                "Fabric Info" => [
                    ID::make()->sortable()->onlyOnIndex(),

                    BelongsTo::make('Location')
                        ->searchable()
                        ->sortable()
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
                        ->creationRules([
                            Rule::unique('fabrics', 'name')->where('location_id', request()->get('location') ?? request()->user()->locationId)
                        ])
                        ->updateRules([
                            Rule::unique('fabrics', 'name')->where('location_id', request()->get('location') ?? request()->user()->locationId)->ignore($this->resource->id)
                        ])
                        ->fillUsing(function ($request, $model) {
                            $model['name'] = Str::title($request->name);
                        })
                        ->help('Your input will be converted to title case. Exp: "title case" to "Title Case".'),

                    TextWrap::make('Name')
                        ->onlyOnIndex()
                        ->sortable()
                        ->wrapMethod('length', 30),

                    TextUppercase::make('Code')
                        ->sortable()
                        ->help('If you want to generate code automatically, leave the field blank.')
                        ->rules('nullable', 'string', 'max:20', 'space', 'alpha_num')
                        ->creationRules([
                            Rule::unique('fabrics', 'code')->where('location_id', request()->get('location') ?? request()->user()->locationId)
                        ])
                        ->updateRules([
                            Rule::unique('fabrics', 'code')->where('location_id', request()->get('location') ?? request()->user()->locationId)->ignore($this->resource->id)
                        ]),

                    Images::make('Image', 'fabric-images')
                        ->croppable(true)
                        ->singleImageRules('max:5000', 'mimes:jpg,jpeg,png')
                        ->hideFromIndex(),

                    Trix::make('Description')
                        ->rules('nullable', 'max:500'),

                    Currency::make('Rate')
                        ->currency('BDT')
                        ->sortable()
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
                        ->sortable()
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
                            if ($this->alertQuantity > $this->quantity) {
                                return "<span class='text-danger'>" . $this->quantity . " " . $this->unit->name . "</span>";
                            }
                            return $this->quantity . " " . $this->unit->name;
                        })
                        ->asHtml()
                        ->sortable()
                        ->exceptOnForms(),

                    BelongsTo::make('Unit')
                        ->hideFromIndex()
                        ->hideWhenUpdating()
                        ->showCreateRelationButton(),

                    AjaxSelect::make('Category', 'category_id')
                        ->rules('required')
                        ->get('/locations/{location}/fabric-categories')
                        ->parent('location')
                        ->onlyOnForms()
                        ->canSee(function ($request) {
                            if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                                return true;
                            }
                            return false;
                        }),

                    BelongsTo::make('Category', 'category', 'App\Nova\FabricCategory')
                        ->exceptOnForms()
                        ->sortable(),

                    BelongsTo::make('Category', 'category', 'App\Nova\FabricCategory')
                        ->onlyOnForms()
                        ->canSee(function ($request) {
                            if (!($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data'))) {
                                return true;
                            }
                            return false;
                        }),

                    BelongsToManyField::make('Suppliers', 'suppliers', 'App\Nova\Supplier')
                        ->hideFromIndex(),

                    Select::make('Status')
                        ->options(ActiveStatus::titleCaseOptions())
                        ->rules('required')
                        ->default(ActiveStatus::ACTIVE())
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
                "Purchase history" => [
                    HasMany::make('Purchase History', 'receiveItems', \App\Nova\FabricReceiveItem::class),
                ],
                "Receive history" => [
                    HasMany::make('Receive History', 'transferReceiveItems', \App\Nova\FabricTransferReceiveItem::class),
                ],
                "Adjust History" => [
                    MorphMany::make('Adjust History', 'adjustQuantities', \App\Nova\AdjustQuantity::class)
                ],

            ]))->withToolbar(),

            new Tabs('Stock Information', [
                "Distribution History" => [
                    HasMany::make('Distribution History', 'distributions', \App\Nova\FabricDistribution::class)
                ],
                "Return History" => [
                    HasMany::make('Return History', 'returnItems', \App\Nova\FabricReturnItem::class)
                ],
                "Transfer History" => [
                    HasMany::make('Transfer History', 'transferItems', \App\Nova\FabricTransferItem::class)
                ]
            ]),

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
            LocationFilter::make('Location', 'location_id')->canSee(function ($request) {
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
            }),

            DependentFilter::make('Category', 'category_id')
                ->dependentOf('location_id')
                ->withOptions(function (Request $request, $filters) {
                    return FabricCategory::where('location_id', $filters['location_id'])
                        ->pluck('name', 'id');
                })->canSee(function ($request) {
                    return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
                }),


            (new FabricCategoryFilter)->canSee(function ($request) {
                return !($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data'));
            }),

            new ActiveStatusFilter,

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
        return [
            (new StockSummary)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can view stock summary of fabrics') || $request->user()->isSuperAdmin();
            }),
            new AlertQuantities,
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
            (new ConvertUnit)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can convert unit of fabrics') || $request->user()->isSuperAdmin();
            })->confirmButtonText('Confirm'),

            (new DownloadPdf)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download fabrics') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download fabrics') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download pdf?"),

            (new DownloadExcel)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download fabrics') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download fabrics') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download excel?"),

            (new AdjustQuantity)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can adjust quantity of fabrics') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can adjust quantity of fabrics') || $request->user()->isSuperAdmin();
                })
                ->onlyOnDetail()
                ->confirmButtonText('Adjust'),
        ];
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];

            $query->orderBy(key(static::$sort), reset(static::$sort));
        }

        if ($request->user()->locationId && !$request->user()->hasPermissionTo('view any locations data')) {
            $query->where('location_id', $request->user()->location_id);
        }

        return $query->with('category', 'location', 'unit');
    }
}