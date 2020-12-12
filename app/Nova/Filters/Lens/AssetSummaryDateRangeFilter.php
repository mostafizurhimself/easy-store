<?php

namespace App\Nova\Filters\Lens;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ampeco\Filters\DateRangeFilter;

class AssetSummaryDateRangeFilter extends DateRangeFilter
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

        return $query->select(self::columns($from, $to));
    }

    /**
     * Get the columns that should be selected.
     *
     * @return array
     */
    protected static function columns($from, $to)
    {
        return [
            'assets.id',
            'locations.name as location_name',
            'assets.name',
            'assets.opening_quantity',
            'asset_categories.name as category_name',

            // Purchases
            DB::raw("(COALESCE((select sum(asset_receive_items.quantity) from asset_receive_items
            where asset_receive_items.date < '$from'
            and asset_receive_items.asset_id = assets.id
            and asset_receive_items.deleted_at is null
            and asset_receive_items.status = 'confirmed'), 0)) as previous_purchase_quantity"),

            DB::raw("(COALESCE((select sum(asset_receive_items.quantity) from asset_receive_items
            where asset_receive_items.date between '$from' and '$to'
            and asset_receive_items.asset_id = assets.id
            and asset_receive_items.deleted_at is null
            and asset_receive_items.status = 'confirmed'), 0)) as purchase_quantity"),

            // Consumes
            DB::raw("(COALESCE((select sum(asset_consumes.quantity) from asset_consumes
            where asset_consumes.created_at < '$from'
            and asset_consumes.asset_id = assets.id
            and asset_consumes.deleted_at  is null), 0)) as previous_consume_quantity"),

            DB::raw("(COALESCE((select sum(asset_consumes.quantity) from asset_consumes
            where asset_consumes.created_at between '$from' and '$to'
            and asset_consumes.asset_id = assets.id
            and asset_consumes.deleted_at  is null), 0)) as consume_quantity"),

            // Returns
            DB::raw("COALESCE((select sum(asset_return_items.quantity) from asset_return_items
            left join asset_return_invoices on asset_return_items.invoice_id = asset_return_invoices.id
            where asset_return_invoices.date < '$from'
            and asset_return_items.asset_id = assets.id
            and asset_return_items.deleted_at is null
            and asset_return_items.status = 'confirmed'), 0) as previous_return_quantity"),

            DB::raw("COALESCE((select sum(asset_return_items.quantity) from asset_return_items
            left join asset_return_invoices on asset_return_items.invoice_id = asset_return_invoices.id
            where asset_return_invoices.date between '$from' and '$to'
            and asset_return_items.asset_id = assets.id
            and asset_return_items.deleted_at is null
            and asset_return_items.status = 'confirmed'), 0) as return_quantity"),

            // Distribution
            DB::raw("COALESCE((select sum(asset_distribution_items.distribution_quantity) from asset_distribution_items
            left join asset_distribution_invoices on asset_distribution_items.invoice_id = asset_distribution_invoices.id
            where asset_distribution_invoices.date < '$from'
            and asset_distribution_items.asset_id = assets.id
            and asset_distribution_items.deleted_at is null
            and asset_distribution_items.status != 'draft'), 0) as previous_distribution_quantity"),

            DB::raw("COALESCE((select sum(asset_distribution_items.distribution_quantity) from asset_distribution_items
            left join asset_distribution_invoices on asset_distribution_items.invoice_id = asset_distribution_invoices.id
            where asset_distribution_invoices.date between '$from' and '$to'
            and asset_distribution_items.asset_id = assets.id
            and asset_distribution_items.deleted_at is null
            and asset_distribution_items.status != 'draft'), 0) as distribution_quantity"),

            // Distribution Receives
            DB::raw("(COALESCE((select sum(asset_distribution_receive_items.quantity) from asset_distribution_receive_items
            where asset_distribution_receive_items.date < '$from'
            and asset_distribution_receive_items.asset_id = assets.id
            and asset_distribution_receive_items.deleted_at is null
            and asset_distribution_receive_items.status = 'confirmed'), 0)) as previous_receive_quantity"),

            DB::raw("(COALESCE((select sum(asset_distribution_receive_items.quantity) from asset_distribution_receive_items
            where asset_distribution_receive_items.date between '$from' and '$to'
            and asset_distribution_receive_items.asset_id = assets.id
            and asset_distribution_receive_items.deleted_at is null
            and asset_distribution_receive_items.status = 'confirmed'), 0)) as receive_quantity"),

            // Adjust
            DB::raw("(COALESCE((select sum(adjust_quantities.quantity) from adjust_quantities
            where adjust_quantities.created_at < '$from'
            and adjust_quantities.adjustable_id = assets.id
            and adjust_quantities.adjustable_type = 'App\\\Models\\\material'
            and adjust_quantities.deleted_at is null), 0)) as previous_adjust_quantity"),

            DB::raw("(COALESCE((select sum(adjust_quantities.quantity) from adjust_quantities
            where adjust_quantities.created_at between '$from' and '$to'
            and adjust_quantities.adjustable_id = assets.id
            and adjust_quantities.adjustable_type = 'App\\\Models\\\Material'
            and adjust_quantities.deleted_at  is null), 0)) as adjust_quantity"),

            "units.name as unit_name",
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
