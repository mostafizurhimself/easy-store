<?php

namespace App\Nova;

use Carbon\Carbon;
use App\Rules\ReceiverRule;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Select;
use App\Enums\RequisitionStatus;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\Lenses\RequisitionItems;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\RequisitionStatusFilter;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use PosLifestyle\DateRangeFilter\DateRangeFilter;
use App\Nova\Lenses\ProductRequisition\Requisitions;
use App\Nova\Actions\ProductRequisitions\ConfirmRequisition;
use App\Nova\Actions\ProductRequisitions\GenerateRequisition;

class ProductRequisition extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ProductRequisition::class;

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 5;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can generate'];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Product Section';

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
        return "Requisitions";
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-comment-alt';
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
            RouterLink::make('Requisition', 'id')
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

            Currency::make('Requisition Amount', 'total_requisition_amount')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),

            Trix::make('Note')
                ->rules('nullable', 'max:500'),

            Files::make('Attachments', 'requisition-attachments')
                ->singleMediaRules('max:5000') // max 5000kb
                ->hideFromIndex(),

            Date::make('Deadline')
                ->sortable()
                ->rules('required'),

            Select::make('Receiver', 'receiver_id')
                ->options(function () {
                    return \App\Models\Location::all()->whereNotIn('id', [request()->user()->locationId])->pluck('name', 'id');
                })
                ->rules('required', new ReceiverRule($request->get('location') ?? $request->user()->locationId))
                ->searchable()
                ->onlyOnForms(),

            Text::make('Receiver', function () {
                return $this->receiver->name;
            })->sortable(),

            Badge::make('Status')->map([
                RequisitionStatus::DRAFT()->getValue()     => 'warning',
                RequisitionStatus::CONFIRMED()->getValue() => 'info',
                RequisitionStatus::PARTIAL()->getValue()   => 'danger',
                RequisitionStatus::DISTRIBUTED()->getValue()  => 'success',
            ])
                ->sortable()
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            HasMany::make('Requisition Items', 'requisitionItems', \App\Nova\ProductRequisitionItem::class),
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

            new RequisitionStatusFilter,
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
            new Requisitions,
            new RequisitionItems
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
            (new ConfirmRequisition)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm product requisitions');
            })
                ->confirmText('Are you sure to confirm this requisition?')
                ->confirmButtonText('Confirm'),

            (new GenerateRequisition)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can generate product requisitions');
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can generate product requisitions') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Generate')
                ->confirmText('Are you sure want to generate requisition now?')
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

        return $query->with('location', 'receiver');
    }
}