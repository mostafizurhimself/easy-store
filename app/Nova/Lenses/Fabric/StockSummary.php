<?php

namespace App\Nova\Lenses\Fabric;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\Number;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\LensRequest;
use App\Nova\Filters\MultipleDateRangeFilter;

class StockSummary extends Lens
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
        return $request->withoutTableOrderPrefix()->withOrdering($request->withFilters(
            $query->select(self::columns())
                ->leftJoin('fabric_receive_items', 'fabrics.id', '=', 'fabric_receive_items.fabric_id')
                ->leftJoin('fabric_distributions', 'fabrics.id', '=', 'fabric_distributions.fabric_id')
                ->leftJoin('fabric_return_items', 'fabrics.id', '=', 'fabric_return_items.fabric_id')
                ->leftJoin('fabric_transfer_items', 'fabrics.id', '=', 'fabric_transfer_items.fabric_id')
                ->join('locations', 'fabrics.location_id', '=', 'locations.id')
                ->join('units', 'fabrics.unit_id', '=', 'units.id')
                ->where('fabrics.deleted_at', "=", null)
                ->groupBy('fabrics.id')
                ->withoutGlobalScopes()
        ));
    }

    /**
     * Get the columns that should be selected.
     *
     * @return array
     */
    protected static function columns()
    {
        return [
            'fabrics.id',
            'locations.name as location_name',
            'fabrics.name',
            DB::raw('sum(COALESCE(fabric_receive_items.quantity, 0)) as purchase_quantity'),
            DB::raw('sum(COALESCE(fabric_distributions.quantity, 0)) as distribution_quantity'),
            DB::raw('sum(COALESCE(fabric_return_items.quantity, 0)) as return_quantity'),
            DB::raw('sum(COALESCE(fabric_transfer_items.transfer_quantity, 0)) as transfer_quantity'),
            'units.name as unit_name',
        ];
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

            Text::make('Location', 'location_name')
                ->sortable(),

            Text::make('Name', 'name')
                ->sortable(),

            Text::make('Purchase Quantity', function () {
                return $this->purchase_quantity . " " . $this->unit_name;
            })
                ->sortable(),

            Text::make('Distribution Quantity', function () {
                    return $this->distribution_quantity . " " . $this->unit_name;
                })
                    ->sortable(),

            Text::make('Return Quantity', function () {
                return $this->return_quantity . " " . $this->unit_name;
            })
                ->sortable(),

            Text::make('Transfer Quantity', function () {
                    return $this->transfer_quantity . " " . $this->unit_name;
                })
                    ->sortable(),
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
        return [
            new MultipleDateRangeFilter([
                ['table' => 'fabric_distributions', 'column' => 'created_at'],
            ]),
        ];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'fabric-stock-summary';
    }
}
