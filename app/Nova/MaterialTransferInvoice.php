<?php

namespace App\Nova;

use Carbon\Carbon;
use App\Rules\ReceiverRule;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\TransferStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Select;
use App\Nova\Filters\DateFilter;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use App\Nova\Lenses\TransferItems;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\Filters\TransferStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use App\Nova\Actions\MaterialTransferInvoices\ConfirmInvoice;
use App\Nova\Lenses\MaterialTransferInvoice\TransferInvoices;
use App\Nova\Actions\MaterialTransferInvoices\GenerateInvoice;
use Eminiarts\Tabs\Tabs;

class MaterialTransferInvoice extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\MaterialTransferInvoice::class;

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 6;

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
        return "Location: " . $this->location->name;
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Transfer Invoices";
    }

    /**
     * Get the navigation label of the resource
     *
     * @return string
     */
    public static function navigationLabel()
    {
        return "Transfers";
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-exchange-alt';
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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [

            (new Tabs("Transfer Invoice Details", [
                "Invoice Info" => [
                    RouterLink::make('Invoice No', 'id')
                        ->sortable()
                        ->withMeta([
                            'label' => $this->readableId,
                        ]),

                    Date::make('Date')
                        ->rules('required')
                        ->default(Carbon::now())
                        ->sortable()
                        ->readonly(),

                    Hidden::make('Date')
                        ->default(Carbon::now())
                        ->hideWhenUpdating(),

                    BelongsTo::make('Location')->sortable()
                        ->searchable()
                        ->canSee(function ($request) {
                            if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                                return true;
                            }
                            return false;
                        }),

                    Currency::make('Transfer Amount', 'total_transfer_amount')
                        ->currency('BDT')
                        ->sortable()
                        ->exceptOnForms(),

                    Currency::make('Receive Amount', 'total_receive_amount')
                        ->currency('BDT')
                        ->sortable()
                        ->exceptOnForms(),

                    Trix::make('Note')
                        ->rules('nullable', 'max:500'),

                    Files::make('Attachments', 'transfer-attachments')
                        ->singleMediaRules('max:5000') // max 5000kb
                        ->hideFromIndex(),

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
                        TransferStatus::DRAFT()->getValue()     => 'warning',
                        TransferStatus::CONFIRMED()->getValue() => 'info',
                        TransferStatus::PARTIAL()->getValue()   => 'danger',
                        TransferStatus::RECEIVED()->getValue()  => 'success',
                    ])
                        ->sortable()
                        ->label(function () {
                            return Str::title(Str::of($this->status)->replace('_', " "));
                        }),

                ],
                "Transfer Items" => [
                    HasMany::make('Transfer Items', 'transferItems', \App\Nova\MaterialTransferItem::class),
                ],
                "Receive Items" => [
                    HasMany::make('Receive Items', 'receiveItems', \App\Nova\MaterialTransferReceiveItem::class),
                ]
            ]))->withToolbar(),


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

            new DateFilter('date'),

            new TransferStatusFilter,
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
            new TransferInvoices,
            new TransferItems,
            // new TransferReceiveItems,
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
            (new ConfirmInvoice)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm material transfer invoices');
            })
                ->confirmButtonText('Confirm'),

            (new GenerateInvoice)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can generate material transfer invoices');
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can generate material transfer invoices') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Generate')
                ->confirmText('Are you sure want to generate invoice now?')
                ->onlyOnDetail(),
        ];
    }
}
