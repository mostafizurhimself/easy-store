<?php

namespace App\Nova;

use Carbon\Carbon;
use Eminiarts\Tabs\Tabs;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\DispatchStatus;
use App\Enums\GatePassStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use App\Nova\Lenses\ReceiveItems;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use App\Nova\Lenses\DispatchItems;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\Actions\CreateGoodsGatePass;
use App\Nova\Filters\DispatchStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\ServiceInvoices\MarkAsDraft;
use App\Nova\Actions\ServiceInvoices\Recalculate;
use PosLifestyle\DateRangeFilter\DateRangeFilter;
use App\Nova\Actions\ServiceInvoices\DownloadExcel;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\ServiceInvoices\ConfirmInvoice;
use App\Nova\Actions\ServiceInvoices\GenerateInvoice;

class ServiceInvoice extends Resource
{

    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ServiceInvoice::class;

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 3;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can generate', 'can download', 'can mark as draft', 'can create gate pass of'];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Service Section';

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
        'provider' => ['name'],
    ];


    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Invoices";
    }

    /**
     * Get the singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return "Service Invoice";
    }

    /**
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel()
    {
        return __('Create Invoice');
    }

    /**
     * Get the text for the update resource button.
     *
     * @return string|null
     */
    public static function updateButtonLabel()
    {
        return __('Update Invoice');
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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            (new Tabs('Service Invoice Details', [
                "Invoice Info" => [
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

                    Trix::make('Description')
                        ->rules('nullable', 'max:500'),

                    Number::make('Total Dispatch', function () {
                        return $this->totalDispatchQuantity;
                    })
                        ->exceptOnForms(),

                    Number::make('Total Receive', function () {
                        return $this->totalReceiveQuantity;
                    })
                        ->exceptOnForms(),

                    Number::make('Total Remaining', function () {
                        return $this->totalRemainingQuantity;
                    })
                        ->exceptOnForms(),

                    Currency::make('Total Dispatch Amount')
                        ->currency('BDT')
                        ->sortable()
                        ->onlyOnDetail(),

                    Currency::make('Total Receive Amount')
                        ->currency('BDT')
                        ->sortable()
                        ->onlyOnDetail(),

                    BelongsTo::make('Provider', 'provider', 'App\Nova\Provider')->searchable(),

                    Badge::make('Status')->map([
                        DispatchStatus::DRAFT()->getValue()     => 'warning',
                        DispatchStatus::CONFIRMED()->getValue() => 'info',
                        DispatchStatus::PARTIAL()->getValue()   => 'danger',
                        DispatchStatus::RECEIVED()->getValue()  => 'success',
                    ])
                        ->sortable()
                        ->label(function () {
                            return Str::title(Str::of($this->status)->replace('_', " "));
                        }),
                ],
                "Receives" => [
                    HasMany::make('Receives', 'receives', \App\Nova\ServiceReceive::class)
                ],


                "Gate Pass" => [

                    Text::make('Gate Pass', function () {
                        return $this->resource->goodsGatePass()->exists() ? $this->resource->goodsGatePass->readableId : null;
                    })
                        ->onlyOnDetail()
                        ->canSee(function () {
                            return $this->resource->goodsGatePass()->exists();
                        }),

                    Number::make('Total CTN', function () {
                        return $this->resource->goodsGatePass()->exists() ? $this->resource->goodsGatePass->details['total_ctn'] : null;
                    })
                        ->onlyOnDetail()
                        ->canSee(function () {
                            return $this->resource->goodsGatePass()->exists();
                        }),

                    Number::make('Total Poly', function () {
                        return $this->resource->goodsGatePass()->exists() ? $this->resource->goodsGatePass->details['total_poly'] : null;
                    })
                        ->onlyOnDetail()
                        ->canSee(function () {
                            return $this->resource->goodsGatePass()->exists();
                        }),

                    Number::make('Total Bag', function () {
                        return $this->resource->goodsGatePass()->exists() ? $this->resource->goodsGatePass->details['total_bag'] : null;
                    })
                        ->onlyOnDetail()
                        ->canSee(function () {
                            return $this->resource->goodsGatePass()->exists();
                        }),

                    Textarea::make('Note', function () {
                        return $this->resource->goodsGatePass()->exists() ? $this->resource->goodsGatePass->note : null;
                    })
                        ->onlyOnDetail()
                        ->canSee(function () {
                            return $this->resource->goodsGatePass()->exists();
                        }),

                    Text::make('Approved By', function () {
                        if ($this->resource->goodsGatePass()->exists()) {
                            return $this->resource->goodsGatePass->approve()->exists() ? $this->resource->goodsGatePass->approve->employee->name : null;
                        }
                    })
                        ->canSee(function () {
                            return $this->resource->goodsGatePass()->exists() && $this->resource->goodsGatePass->approve()->exists();
                        })
                        ->onlyOnDetail(),

                    DateTime::make('Approved At', function () {
                        if ($this->resource->goodsGatePass()->exists()) {
                            return $this->resource->goodsGatePass->approve()->exists() ? $this->resource->goodsGatePass->approve->createdAt : null;
                        }
                    })
                        ->canSee(function () {
                            return $this->resource->goodsGatePass()->exists() && $this->resource->goodsGatePass->approve()->exists();
                        })
                        ->onlyOnDetail(),

                    Text::make('Passed By', function () {
                        if ($this->resource->goodsGatePass()->exists()) {
                            return $this->resource->goodsGatePass->passedBy ? $this->resource->goodsGatePass->passedBy->name : null;
                        }
                    })
                        ->canSee(function () {
                            return $this->resource->goodsGatePass()->exists() && $this->resource->goodsGatePass->passedBy;
                        })
                        ->onlyOnDetail(),

                    DateTime::make('Passed At', function () {
                        if ($this->resource->goodsGatePass()->exists()) {
                            return $this->resource->goodsGatePass->passedBy ? $this->resource->goodsGatePass->passedAt : null;
                        }
                    })
                        ->canSee(function () {
                            return $this->resource->goodsGatePass()->exists() && $this->resource->goodsGatePass->passedBy;
                        })
                        ->onlyOnDetail(),

                    Text::make('Status', function () {
                        if ($this->resource->goodsGatePass()->exists()) {
                            if ($this->resource->goodsGatePass->status == GatePassStatus::DRAFT()) {
                                return "<span class='whitespace-no-wrap px-2 py-1 rounded-full uppercase text-xs font-bold bg-warning-light text-warning-dark'>" . $this->resource->goodsGatePass->status . "</span>";
                            }

                            if ($this->resource->goodsGatePass->status == GatePassStatus::CONFIRMED()) {
                                return "<span class='whitespace-no-wrap px-2 py-1 rounded-full uppercase text-xs font-bold bg-info-light text-info-dark'>" . $this->resource->goodsGatePass->status . "</span>";
                            }

                            if ($this->resource->goodsGatePass->status == GatePassStatus::PASSED()) {
                                return "<span class='whitespace-no-wrap px-2 py-1 rounded-full uppercase text-xs font-bold bg-success-light text-success-dark'>" . $this->resource->goodsGatePass->status . "</span>";
                            }
                        }
                    })
                        ->asHtml()
                        ->canSee(function () {
                            return $this->resource->goodsGatePass()->exists();
                        })
                        ->onlyOnDetail(),
                ]
            ]))->withToolbar(),


            HasMany::make('Dispatches', 'dispatches', \App\Nova\ServiceDispatch::class),

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

            new DispatchStatusFilter,
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
            new DispatchItems,
            new ReceiveItems
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
                return $request->user()->hasPermissionTo('can mark as draft service invoices') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can mark as draft service invoices') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Mark As Draft')
                ->confirmText('Are you sure want to mark the invoice as draft')
                ->onlyOnDetail(),

            (new ConfirmInvoice)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm service invoices') || $request->user()->isSuperAdmin();
            })->confirmButtonText('Confirm'),

            (new GenerateInvoice)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can generate service invoices') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can generate service invoices') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Generate')
                ->confirmText('Are you sure want to generate invoice now?')
                ->onlyOnDetail(),

            (new CreateGoodsGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can create gate pass of service invoices') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can create gate pass of service invoices') || $request->user()->isSuperAdmin();
                })
                ->onlyOnDetail()
                ->confirmButtonText('Create Or Update'),

            (new DownloadExcel)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download service invoices') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download service invoices') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download excel?"),
        ];
    }
}