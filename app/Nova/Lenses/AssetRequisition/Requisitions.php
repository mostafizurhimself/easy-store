<?php

namespace App\Nova\Lenses\AssetRequisition;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\Badge;
use App\Enums\RequisitionStatus;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Easystore\RouterLink\RouterLink;
use Laravel\Nova\Http\Requests\LensRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;

class Requisitions extends Lens
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
                ->where('status', '!=', RequisitionStatus::DRAFT())
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

            ID::make('ID', 'id'),

            Text::make('Requisition', function () {
                return $this->readableId;
            })
                ->sortable(),

            Date::make('Date')
                ->sortable(),

            Text::make('Location', function () {
                return $this->location->name;
            })
                ->sortable(),


            Currency::make('Requisition Amount', 'total_requisition_amount')
                ->currency('BDT')
                ->sortable(),

            Date::make('Deadline')
                ->sortable(),

            Text::make('Receiver', function () {
                return $this->receiver->name;
            })
                ->sortable(),

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
        return 'asset-requisition-requisitions';
    }
}