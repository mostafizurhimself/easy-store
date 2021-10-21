<?php

namespace App\Nova;

use Carbon\Carbon;
use App\Enums\ReturnStatus;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Hidden;
use App\Nova\Lenses\ReturnItems;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\Filters\ReturnStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use PosLifestyle\DateRangeFilter\DateRangeFilter;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\MaterialReturnInvoices\MarkAsDraft;
use App\Nova\Actions\MaterialReturnInvoices\Recalculate;
use App\Nova\Actions\MaterialReturnInvoices\ConfirmInvoice;
use App\Nova\Actions\MaterialReturnInvoices\GenerateInvoice;

class MaterialReturnInvoice extends Resource
{
    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\MaterialReturnInvoice::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can generate', 'can recalculate', 'can mark as draft'];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Material Section';

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
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        return "Location: {$this->location->name}";
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Return Invoices";
    }

    /**
     * Get the navigation label of the resource
     *
     * @return string
     */
    public static function navigationLabel()
    {
        return "Returns";
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-undo-alt';
    }


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
            RouterLink::make('Invoice', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ])
                ->sortable(),

            Date::make('Date')
                ->rules('required')
                ->default(Carbon::now())
                ->sortable()
                ->readonly(),

            Hidden::make('Date')
                ->default(Carbon::now())
                ->hideWhenUpdating(),

            BelongsTo::make('Location')
                ->searchable()
                ->sortable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Supplier', 'supplier', "App\Nova\Supplier")
                ->searchable()
                ->sortable(),

            Currency::make('Total Amount', 'total_return_amount')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),

            Badge::make('Status')->map([
                ReturnStatus::DRAFT()->getValue()     => 'warning',
                ReturnStatus::CONFIRMED()->getValue() => 'info',
                ReturnStatus::BILLED()->getValue()    => 'danger',
            ])
                ->sortable()
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            Trix::make('Note')
                ->rules('nullable', 'max:500'),

            Text::make('Approved By', function () {
                return $this->approve ? $this->approve->employee->name : null;
            })
                ->canSee(function () {
                    return $this->approve()->exists();
                })
                ->sortable()
                ->onlyOnDetail(),

            HasMany::make('Return Items', 'returnItems', \App\Nova\MaterialReturnItem::class),
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

            new DateRangeFilter('Date Between', 'date'),

            new ReturnStatusFilter,
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
            new ReturnItems
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
            (new Recalculate)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can recalculate material return invoices') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can recalculate material return invoices') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Recalculate')
                ->confirmText('Are you sure want to recalculate invoice now?'),

            (new MarkAsDraft)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can mark as draft material return invoices') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can mark as draft material return invoices') || $request->user()->isSuperAdmin();
                })
                ->onlyOnDetail()
                ->confirmButtonText('Mark As Draft')
                ->confirmText('Are you sure want to mark the return invoice as draft?'),

            (new ConfirmInvoice)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm material return invoices');
            }),

            (new GenerateInvoice)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can generate material return invoices');
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can generate material return invoices') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Generate')
                ->confirmText('Are you sure want to generate invoice now?')
                ->onlyOnDetail(),
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

        return $query->with('location', 'supplier');
    }
}