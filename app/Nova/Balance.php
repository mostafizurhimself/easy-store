<?php

namespace App\Nova;

use Carbon\Carbon;
use R64\NovaFields\Trix;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use App\Enums\BalanceStatus;
use App\Enums\PaymentMethod;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphMany;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\Balances\ConfirmBalance;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Titasgailius\SearchRelations\SearchesRelations;

class Balance extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Balance::class;

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
    public static $priority = 4;

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
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel()
    {
        return __('Add Balance');
    }

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'location' => ['name'],
        'expenser' => ['name'],
    ];
    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
      return 'fas fa-wallet';
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
            RouterLink::make('Balance Id', 'id')
            ->withMeta([
                'label' => $this->readableId,
            ])
            ->sortable(),

            Date::make('Date')
                ->rules('required')
                ->sortable()
                ->default(Carbon::now()),

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
                if (!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin() && !$request->user()->isExpenser()) {
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

        Currency::make('Amount')
            ->currency('BDT')
            ->sortable()
            ->rules('required', 'numeric', 'min:0'),

        Select::make('Payment Method', 'method')
            ->options(PaymentMethod::titleCaseOptions())
            ->rules('required')
            ->onlyOnForms(),

        Text::make('Payment Method', function(){
            return Str::title(Str::of($this->method)->replace('_', " "));
        })
            ->exceptOnForms()
            ->sortable(),

        Files::make('Attachments', 'balance-attachments')
            ->singleMediaRules('max:5000') // max 5000kb
            ->hideFromIndex(),

        Text::make('Approved By', function(){
                return $this->approve ? $this->approve->employee->name : null;
            })
            ->canSee(function(){
                return $this->approve()->exists();
            })
            ->onlyOnDetail(),


        Badge::make('Status')->map([
                BalanceStatus::DRAFT()->getValue()   => 'warning',
                BalanceStatus::CONFIRMED()->getValue() => 'info',
            ])
            ->sortable()
            ->label(function(){
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
           LocationFilter::make('Location', 'location_id')->canSee(function($request){
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
            }),
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
            (new ConfirmBalance)->canSee(function($request){
                return $request->user()->hasPermissionTo('can confirm balances');
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

        // Query for expensers
        if ($request->user()->isExpenser()) {
            return $query->where('expenser_id', $request->user()->expenser->id);
        }

        // Query for non expensers
        if ($request->user()->locationId && !$request->user()->hasPermissionTo('view any locations data')) {
            $query->where('location_id', $request->user()->location_id);
        }

        return $query;
    }
}
