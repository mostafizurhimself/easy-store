<?php

namespace App\Nova\Filters\Lens;

use Carbon\Carbon;
use App\Traits\ActiveScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ampeco\Filters\DateRangeFilter;

class FabricSummaryDateRangeFilter extends DateRangeFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = "Date Between";

    /**
     * The tables name and date column of the filter.
     *
     * @var array
     */
    public $params;

    /**
     * Set the filterable table and date column
     *
     * @return void
     */
    public function __construct($params = null)
    {
        $this->params = $params;
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $from = Carbon::parse($value[0])->startOfDay();
        $to = Carbon::parse($value[1])->endOfDay();

        return $query->select(self::columns())
            ->leftJoin('fabric_receive_items', function ($join) use ($from, $to) {
                $join->on('fabrics.id', '=', 'fabric_receive_items.fabric_id')
                    ->whereBetween('fabric_receive_items.date', [$from, $to])
                    ->where('fabric_receive_items.deleted_at', "=", null);
            })
            ->leftJoin('fabric_distributions', function ($join) use ($from, $to) {
                $join->on('fabrics.id', '=', 'fabric_distributions.fabric_id')
                    ->whereBetween('fabric_distributions.created_at', [$from, $to])
                    ->where('fabric_distributions.deleted_at', "=", null);
            })
            ->leftJoin(DB::raw('(select fabric_return_items.fabric_id, fabric_return_items.quantity, fabric_return_items.deleted_at, fabric_return_invoices.date
                from fabric_return_items
                join fabric_return_invoices on fabric_return_items.invoice_id = fabric_return_invoices.id)
                as return_items'), function ($join) use ($from, $to) {
                $join->on('fabrics.id', '=', 'return_items.fabric_id')
                    ->whereBetween('return_items.date', [$from, $to])
                    ->where('return_items.deleted_at', "=", null);
            })
            ->leftJoin(DB::raw('(select
                fabric_transfer_items.fabric_id, fabric_transfer_items.transfer_quantity, fabric_transfer_items.deleted_at, fabric_transfer_invoices.date
                from fabric_transfer_items
                join fabric_transfer_invoices on fabric_transfer_items.invoice_id = fabric_transfer_invoices.id)
                as transfer_items'), function ($join) use ($from, $to) {
                $join->on('fabrics.id', '=', 'transfer_items.fabric_id')
                    ->whereBetween('transfer_items.date', [$from, $to])
                    ->where('transfer_items.deleted_at', "=", null);
            });
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
            DB::raw('sum(COALESCE(return_items.quantity, 0)) as return_quantity'),
            DB::raw('sum(COALESCE(transfer_items.transfer_quantity, 0)) as transfer_quantity'),
            'units.name as unit_name',
        ];
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [];
    }
}
