<?php

namespace App\Nova\Lenses\AssetDistributionInvoice;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\HasMany;
use App\Enums\DistributionStatus;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\LensRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;

class DistributionInvoices extends Lens
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
            $query->where('receiver_id', $request->user()->locationId)
                    ->where('status','!=', DistributionStatus::DRAFT())
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

            Text::make('Invoice No', function(){
                return $this->readableId;
            }),

            Date::make('Date')
                ->rules('required')
                ->default(Carbon::now())
                ->hideWhenUpdating()
                ->readonly(),

            Text::make('Location', function(){
                    return $this->location->name;
                }),


            Currency::make('Distribution Amount', 'total_distribution_amount')
                ->currency('BDT')
                ->onlyOnDetail(),

            Text::make('Receiver', function(){
                return $this->receiver->name;
            }),

            BelongsTo::make('Requisition', 'requisition', "App\Nova\AssetRequisition")
                ->exceptOnForms(),

            Badge::make('Status')->map([
                    DistributionStatus::DRAFT()->getValue()     => 'warning',
                    DistributionStatus::CONFIRMED()->getValue() => 'info',
                    DistributionStatus::PARTIAL()->getValue()   => 'danger',
                    DistributionStatus::RECEIVED()->getValue()  => 'success',
                ])
                ->label(function(){
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
        return 'asset-distribution-invoice-distribution-invoices';
    }
}
