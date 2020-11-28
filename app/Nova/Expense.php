<?php

namespace App\Nova;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use App\Enums\ExpenseStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use App\Nova\AssetPurchaseOrder;
use App\Rules\ExpenseAmountRule;
use Laravel\Nova\Fields\MorphTo;
use App\Nova\FabricPurchaseOrder;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphMany;
use App\Nova\MaterialPurchaseOrder;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\Filters\ExpenseStatusFilter;
use App\Rules\ExpenseAmountRuleForUpdate;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\Expenses\ConfirmExpense;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Titasgailius\SearchRelations\SearchesRelations;

class Expense extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Expense::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm'];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">10</span>Expense Section';

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 3;

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
        'expenser' => ['name'],
        'category' => ['name'],
    ];

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        $subtitle = "Expenser: {$this->expenser->name}";
        $subtitle .= ", Location: {$this->location->name}";
        return $subtitle;
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-money-bill';
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
            RouterLink::make('Expense Id', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ])
                ->sortable(),

            Date::make('Date')
                ->rules('required')
                ->default(Carbon::now())
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

            AjaxSelect::make('Expenser', 'expenser_id')
                ->rules('required')
                ->get('/locations/{location}/expensers')
                ->parent('location')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Expenser', 'expenser', 'App\Nova\Expenser')
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Expenser', 'expenser', 'App\Nova\Expenser')
                ->searchable()
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ((!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()) && !$request->user()->isExpenser()) {
                        return true;
                    }
                    return false;
                }),


            AjaxSelect::make('Category', 'category_id')
                ->rules('required')
                ->get('/locations/{location}/expense-categories')
                ->parent('location')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Category', 'category', 'App\Nova\ExpenseCategory')
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Category', 'category', 'App\Nova\ExpenseCategory')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if (!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Trix::make('Description')
                ->rules('nullable', 'max:500'),

            Text::make('Reference')
                ->rules('nullable', 'string', 'max:200')
                ->hideFromIndex()
                ->sortable(),

            MorphTo::make('Purchase Type', 'purchase')->types([
                FabricPurchaseOrder::class,
                MaterialPurchaseOrder::class,
                AssetPurchaseOrder::class,
            ])
                ->nullable()
                ->hideFromIndex()
                ->searchable(),

            Currency::make('Amount')
                ->currency('BDT')
                ->rules('required', 'numeric', 'min:0')
                ->onlyOnForms()
                ->creationRules(new ExpenseAmountRule($request->get('expenser_id') ?? $request->get('expenser')))
                ->updateRules(new ExpenseAmountRuleForUpdate($request->get('expenser_id'), $this->resource->amount, $this->resource->expenserId))
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Currency::make('Amount')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),

            Files::make('Attachments', 'expense-attachments')
                ->singleMediaRules('max:5000') // max 5000kb
                ->hideFromIndex(),

            Text::make('Approved By', function () {
                return $this->approve ? $this->approve->employee->name : null;
            })
                ->canSee(function () {
                    return $this->approve()->exists();
                })
                ->onlyOnDetail(),

            Badge::make('Status')->map([
                ExpenseStatus::DRAFT()->getValue()   => 'warning',
                ExpenseStatus::CONFIRMED()->getValue() => 'info',
            ])
                ->sortable()
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            MorphMany::make('Activities', 'activities', "App\Nova\ActivityLog"),

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

            new ExpenseStatusFilter,
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
            (new ConfirmExpense)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm expenses');
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

        // // Get for expensers
        // if ($request->user()->isExpenser()) {
        //     return $query->with(['expenser' => function($expenser) use($request){
        //         $expenser->where('user_id', $request->user()->id);
        //     }]);
        // }

        // Query for non expensers
        if ($request->user()->locationId && !$request->user()->hasPermissionTo('view any locations data')) {
            $query->where('location_id', $request->user()->location_id);
        }

        return $query;
    }
}
