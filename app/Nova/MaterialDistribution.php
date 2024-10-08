<?php

namespace App\Nova;

use App\Models\Floor;
use App\Models\Employee;
use App\Models\Material;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use App\Enums\DistributionStatus;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\EmployeeFilter;
use App\Nova\Filters\LocationFilter;
use App\Nova\Filters\MaterialFilter;
use Easystore\RouterLink\RouterLink;
use App\Rules\DistributionQuantityRule;
use AwesomeNova\Filters\DependentFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\FloorFilterViaEmployee;
use App\Nova\Filters\DistributionStatusFilter;
use App\Rules\DistributionQuantityRuleForUpdate;
use PosLifestyle\DateRangeFilter\DateRangeFilter;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Filters\DependentFloorFilterViaEmployee;
use App\Nova\Actions\MaterialDistributions\DownloadPdf;
use App\Nova\Actions\MaterialDistributions\DownloadExcel;
use App\Nova\Actions\MaterialDistributions\ConfirmDistribution;

class MaterialDistribution extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\MaterialDistribution::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can download'];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Material Section';

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-truck';
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Distributions";
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'readable_id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'readable_id',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'location' => ['name'],
        'material' => ['code', 'name'],
        'receiver' => ['readable_id', 'first_name', 'last_name'],
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
            // ID::make()->sortable(),

            BelongsTo::make('Location')
                ->searchable()
                ->sortable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            RouterLink::make('Number', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ])
                ->sortable(),

            BelongsTo::make('Material')
                ->exceptOnForms()
                ->sortable(),

            Select::make('Material', 'material_id')
                ->options(function () use ($request) {
                    if (!$request->isResourceIndexRequest()) {
                        return \App\Models\Material::where('location_id', $request->user()->locationId)->pluck('name', 'id');
                    }
                    return [];
                })
                ->searchable()
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if (!($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data'))) {
                        return true;
                    }
                    return false;
                }),

            AjaxSelect::make('Material', 'material_id')
                ->rules('required')
                ->get('/locations/{location}/materials')
                ->parent('location')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Text::make('Material Name', function () {
                return $this->material->name;
            })
                ->exceptOnForms()
                ->sortable()
                ->hideFromIndex(),

            Number::make('Quantity')
                ->creationRules(function ($request) {
                    if ($request->isCreateOrAttachRequest()) {
                        return [new DistributionQuantityRule(\App\Nova\MaterialDistribution::uriKey(), $request->get('material_id') ?? $request->get('material'))];
                    }
                    return [];
                })
                ->updateRules(function ($request) {
                    if ($request->isUpdateOrUpdateAttachedRequest()) {
                        return [new DistributionQuantityRuleForUpdate(\App\Nova\MaterialDistribution::uriKey(), $request->get('material_id') ?? $request->get('material'), $this->resource->quantity, $this->resource->materialId)];
                    }
                    return [];
                })
                ->rules('required', 'numeric', 'min:1')
                ->sortable()
                ->onlyOnForms(),

            Text::make('Quantity', function () {
                return $this->quantity . " " . $this->unitName;
            })
                ->exceptOnForms()
                ->sortable(),



            Currency::make('Rate')
                ->currency('BDT')
                ->exceptOnForms()
                ->sortable()
                ->hideFromIndex(),

            Currency::make('Amount')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms()
                ->hideFromIndex(),

            Trix::make('Description')
                ->rules('nullable', 'max:500'),

            BelongsTo::make('Receiver', 'receiver', "App\Nova\Employee")
                ->sortable()
                ->exceptOnForms(),

            Text::make('Receiver Name', function () {
                return $this->receiver->name;
            })
                ->sortable()
                ->hideFromIndex(),

            BelongsTo::make('Receiver', 'receiver', "App\Nova\Employee")
                ->searchable()
                ->onlyOnForms()
                ->sortable()
                ->canSee(function ($request) {
                    if (!($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data'))) {
                        return true;
                    }
                    return false;
                }),

            AjaxSelect::make('Receiver', 'receiver_id')
                ->rules('required')
                ->get('/locations/{location}/employees')
                ->parent('location')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            DateTime::make('Distributed At', 'created_at')
                ->exceptOnForms()
                ->sortable(),

            Badge::make('Status')->map([
                DistributionStatus::DRAFT()->getValue()     => 'warning',
                DistributionStatus::CONFIRMED()->getValue() => 'info',
            ])
                ->sortable()
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
            LocationFilter::make('Location', 'location_id')->canSee(function ($request) {
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
            }),

            DependentFilter::make('Material', 'material_id')
                ->dependentOf('location_id')
                ->withOptions(function (Request $request, $filters) {
                    return Material::where('location_id', $filters['location_id'])
                        ->orderBy('name')
                        ->pluck('name', 'id');
                })->canSee(function ($request) {
                    return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
                }),

            (new MaterialFilter)->canSee(function ($request) {
                return !($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data'));
            }),

            DependentFilter::make('Receiver', 'receiver_id')
                ->dependentOf('location_id')
                ->withOptions(function (Request $request, $filters) {
                    return Employee::where('location_id', $filters['location_id'])
                        ->orderBy('first_name')
                        ->get()
                        ->pluck('nameWithId', 'id');
                })->canSee(function ($request) {
                    return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
                }),

            (new EmployeeFilter('receiver_id', "Receiver"))->canSee(function ($request) {
                return !($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data'));
            }),

            new DateRangeFilter,

            new DistributionStatusFilter,
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

            (new DownloadPdf)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download material distributions') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download material distributions') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download pdf?"),

            (new DownloadExcel)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download material distributions') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download material distributions') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download excel?"),

            (new ConfirmDistribution)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm material distributions') || $request->user()->isSuperAdmin();
            }),

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

        return $query->with('location', 'material', 'unit', 'receiver');
    }
}