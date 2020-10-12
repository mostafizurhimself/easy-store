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
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\ServiceTransferInvoice\GenerateInvoice;
use App\Nova\Actions\ServiceTransferInvoices\ConfirmInvoice;
use App\Nova\Lenses\ServiceTransferInvoice\TransferInvoices;

class ServiceTransferInvoice extends Resource
{
    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ServiceTransferInvoice::class;

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 4;

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
    public static $group = '<span class="hidden">07</span>Service Section';

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
        'receiver' => ['name'],
    ];

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
                ]),

            Date::make('Date')
                ->rules('required')
                ->default(Carbon::now())
                ->readonly(),

            Hidden::make('Date')
                ->default(Carbon::now())
                ->hideWhenUpdating(),

            BelongsTo::make('Location')->searchable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Trix::make('Description')
                ->rules('nullable', 'max:500'),

            Currency::make('Total Transfer Amount')
                ->currency('BDT')
                ->exceptOnForms(),

            Currency::make('Total Receive Amount')
                ->currency('BDT')
                ->exceptOnForms(),

            Select::make('Receiver', 'receiver_id')
                ->options(function(){
                    return \App\Models\Location::all()->whereNotIn('id', [request()->user()->locationId])->pluck('name', 'id');
                })
                ->rules('required', new ReceiverRule($request->get('location') ?? $request->user()->locationId))
                ->onlyOnForms(),

            Text::make('Receiver', function(){
                return $this->receiver->name;
            }),

            Badge::make('Status')->map([
                TransferStatus::DRAFT()->getValue()     => 'warning',
                TransferStatus::CONFIRMED()->getValue() => 'info',
                TransferStatus::PARTIAL()->getValue()   => 'danger',
                TransferStatus::RECEIVED()->getValue()  => 'success',
            ])
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            HasMany::make('Transfer Items', 'transferItems',  \App\Nova\ServiceTransferItem::class),
            HasMany::make('Receive Items', 'receiveItems', \App\Nova\ServiceTransferReceiveItem::class)

        ];;
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
            (new LocationFilter)->canSee(function($request){
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view all locations data');
            })
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
            (new ConfirmInvoice)->canSee(function($request){
                return $request->user()->hasPermissionTo('can confirm service transfer invoices');
            })
            ->confirmButtonText('Confirm')
            ->confirmText('Are you sure want to confirm?'),

            (new GenerateInvoice)->canSee(function($request){
                return $request->user()->hasPermissionTo('can generate service invoices');
            })
            ->canRun(function($request){
                return $request->user()->hasPermissionTo('can generate service invoices') || $request->user()->isSuperAdmin();
            })
            ->confirmButtonText('Generate')
            ->confirmText('Are you sure want to generate invoice now?')
            ->onlyOnDetail(),
        ];
    }
}
