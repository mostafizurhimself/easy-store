<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use App\Nova\Filters\DateRangeFilter;
use App\Enums\DistributionStatus;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Rules\DistributionQuantityRule;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\DistributionStatusFilter;
use App\Rules\DistributionQuantityRuleForUpdate;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\FinishingDistributions\GenerateInvoice;
use App\Nova\Actions\FabricDistributions\ConfirmDistribution;

class FabricDistribution extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\FabricDistribution';

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">04</span>Fabrics Section';

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can generate'];

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 6;

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
        'fabric' => ['code', 'name'],
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

            RouterLink::make('Number', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ])
                ->sortable(),

            BelongsTo::make('Location')
                ->searchable()
                ->sortable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Fabric')
                ->exceptOnForms()
                ->searchable()
                ->sortable(),

            Select::make('Fabric', 'fabric_id')
                ->options(\App\Models\Fabric::where('location_id', $request->user()->locationId)->pluck('name', 'id'))
                ->searchable()
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if (!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            AjaxSelect::make('Fabric', 'fabric_id')
                ->rules('required')
                ->get('/locations/{location}/fabrics')
                ->parent('location')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Text::make('Fabrics Name', function () {
                return $this->fabric->name;
            })
                ->exceptOnForms()
                ->sortable()
                ->hideFromIndex(),

            Number::make('Quantity')
                ->creationRules(new DistributionQuantityRule(\App\Nova\FabricDistribution::uriKey(), $request->get('fabric_id') ?? $request->get('fabric')))
                ->updateRules(new DistributionQuantityRuleForUpdate(\App\Nova\FabricDistribution::uriKey(), $request->get('fabric_id') ?? $request->get('fabric'), $this->resource->quantity, $this->resource->fabricId))
                ->rules('required', 'numeric', 'min:1')
                ->onlyOnForms(),

            Text::make('Quantity', function () {
                return $this->quantity . " " . $this->unitName;
            })
                ->sortable()
                ->exceptOnForms(),



            Currency::make('Rate')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms()
                ->hideFromIndex(),

            Currency::make('Amount')
                ->currency('BDT')
                ->exceptOnForms()
                ->sortable()
                ->hideFromIndex(),

            Trix::make('Description')
                ->rules('nullable', 'max:500'),

            BelongsTo::make('Receiver', 'receiver', "App\Nova\Employee")
                ->searchable()
                ->exceptOnForms(),

            Text::make('Receiver Name', function () {
                return $this->receiver->fullName;
            })
                ->hideFromIndex(),

            BelongsTo::make('Receiver', 'receiver', "App\Nova\Employee")->searchable()
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if (!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()) {
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

            DateTime::make('Distributed At', 'Created At')
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
            (new ConfirmDistribution)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm fabric distributions') || $request->user()->isSuperAdmin();
            }),

            (new GenerateInvoice)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can generate fabric distributions') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can generate fabric distributions') || $request->user()->isSuperAdmin();
                })
                ->onlyOnTableRow()
                ->withoutConfirmation(),
        ];
    }
}
