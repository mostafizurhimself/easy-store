<?php

namespace App\Nova\Lenses\FabricTransferInvoice;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\TransferStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\LensRequest;

class TransferInvoices extends Lens
{
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->with('location', 'receiver')->where('receiver_id', $request->user()->locationId)
                ->where('status', '!=', TransferStatus::DRAFT())
                ->orderBy('id', 'DESC')
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('Invoice No', function () {
                return $this->readableId;
            })
                ->sortable(),

            Date::make('Date')
                ->sortable(),

            Text::make('Location', function () {
                return $this->location->name;
            })
                ->sortable(),

            Currency::make('Total Transfer Amount')
                ->sortable()
                ->currency('BDT'),

            Currency::make('Total Receive Amount')
                ->sortable()
                ->currency('BDT'),


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
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'fabric-transfer-invoice-transfer-invoices';
    }
}