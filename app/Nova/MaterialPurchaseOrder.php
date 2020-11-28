<?php

namespace App\Nova;

use Carbon\Carbon;
use Eminiarts\Tabs\Tabs;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use App\Enums\PaymentMethod;
use Illuminate\Http\Request;
use App\Enums\PurchaseStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use App\Nova\Lenses\ReceiveItems;
use Laravel\Nova\Fields\Currency;
use App\Nova\Lenses\PurchaseItems;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\Filters\DateRangeFilter;
use App\Nova\Filters\PurchaseStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\MaterialPurchaseOrders\MarkAsDraft;
use App\Nova\Actions\MaterialPurchaseOrders\Recalculate;
use App\Nova\Actions\MaterialPurchaseOrders\ConfirmPurchase;
use App\Nova\Actions\MaterialPurchaseOrders\GeneratePurchaseOrder;

class MaterialPurchaseOrder extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\MaterialPurchaseOrder';

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can generate', 'can mark as draft'];

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 3;

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">05</span>Material Section';

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
        return "Supplier: {$this->supplier->name}";
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Purchases";
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Material Purchase Order';
    }

    /**
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel()
    {
        return __('Create Purchase');
    }

    /**
     * Get the text for the update resource button.
     *
     * @return string|null
     */
    public static function updateButtonLabel()
    {
        return __('Update Purchase');
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-file-invoice';
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
        'supplier' => ['name'],
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

            (new Tabs("Purchase Details", [
                "Purchase Info" => [
                    RouterLink::make('PO Number', 'id')
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

                    Date::make('Date')
                        ->rules('required')
                        ->default(Carbon::now())
                        ->sortable()
                        ->readonly(),

                    Hidden::make('Date')
                        ->default(Carbon::now())
                        ->hideWhenUpdating(),

                    BelongsTo::make('Supplier', 'supplier', 'App\Nova\Supplier')
                        ->sortable()
                        ->searchable(),

                    Currency::make('Purchase Amount', 'total_purchase_amount')
                        ->currency('BDT')
                        ->sortable()
                        ->exceptOnForms(),

                    Currency::make('Receive Amount', 'total_receive_amount')
                        ->currency('BDT')
                        ->sortable()
                        ->exceptOnForms(),

                    Badge::make('Status')->map([
                        PurchaseStatus::DRAFT()->getValue()     => 'warning',
                        PurchaseStatus::CONFIRMED()->getValue() => 'info',
                        PurchaseStatus::PARTIAL()->getValue()   => 'danger',
                        PurchaseStatus::RECEIVED()->getValue()  => 'success',
                        PurchaseStatus::BILLED()->getValue()    => 'danger',
                    ])
                        ->sortable()
                        ->label(function () {
                            return Str::title(Str::of($this->status)->replace('_', " "));
                        }),

                    Trix::make('Note')
                        ->rules('nullable', 'max:2000'),

                    Text::make('Approved By', function () {
                        return $this->approve ? $this->approve->employee->name : null;
                    })
                        ->canSee(function () {
                            return $this->approve()->exists();
                        })
                        ->sortable()
                        ->onlyOnDetail(),
                ],
                "Receive Items" => [
                    HasMany::make('Receive Items', 'receiveItems', 'App\Nova\MaterialReceiveItem'),
                ]
            ]))->withToolbar(),

            HasMany::make('Purchase Items', 'purchaseItems', 'App\Nova\MaterialPurchaseItem'),

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

            new DateRangeFilter('date'),

            new PurchaseStatusFilter,
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
            new PurchaseItems,
            new ReceiveItems,
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
                return $request->user()->isSuperAdmin();
            })->canRun(function ($request) {
                return $request->user()->isSuperAdmin();
            }),

            (new MarkAsDraft)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can mark as draft material purchase orders') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can mark as draft material purchase orders') || $request->user()->isSuperAdmin();
                })
                ->onlyOnDetail()
                ->confirmButtonText('Mark As Draft')
                ->confirmText('Are you sure want to mark the purchase order as draft?'),

            (new ConfirmPurchase)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm material purchase orders');
            }),

            (new GeneratePurchaseOrder)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can generate material purchase orders');
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can generate material purchase orders') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Generate')
                ->confirmText('Are you sure want to generate purchase order?')
                ->onlyOnDetail(),
        ];
    }
}
