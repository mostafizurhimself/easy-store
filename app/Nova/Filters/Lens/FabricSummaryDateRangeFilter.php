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
            'fabrics.id',
            'locations.name as location_name',
            'fabrics.name',
            'fabrics.opening_quantity',
            'fabric_categories.name as category_name',

            // Purchases
            DB::raw("(COALESCE((select sum(fabric_receive_items.quantity) from fabric_receive_items
            where fabric_receive_items.date < '$from'
            and fabric_receive_items.fabric_id = fabrics.id
            and fabric_receive_items.deleted_at is null
            and fabric_receive_items.status = 'confirmed'), 0)) as previous_purchase_quantity"),

            DB::raw("(COALESCE((select sum(fabric_receive_items.quantity) from fabric_receive_items
            where fabric_receive_items.date between '$from' and '$to'
            and fabric_receive_items.fabric_id = fabrics.id
            and fabric_receive_items.deleted_at is null
            and fabric_receive_items.status = 'confirmed'), 0)) as purchase_quantity"),

            // Distributions
            DB::raw("(COALESCE((select sum(fabric_distributions.quantity) from fabric_distributions
            where fabric_distributions.created_at < '$from'
            and fabric_distributions.fabric_id = fabrics.id
            and fabric_distributions.status = 'confirmed'
            and fabric_distributions.deleted_at is null), 0)) as previous_distribution_quantity"),

            DB::raw("(COALESCE((select sum(fabric_distributions.quantity) from fabric_distributions
            where fabric_distributions.created_at between '$from' and '$to'
            and fabric_distributions.fabric_id = fabrics.id
            and fabric_distributions.status = 'confirmed'
            and fabric_distributions.deleted_at  is null), 0)) as distribution_quantity"),

            // Returns
            DB::raw("COALESCE((select sum(fabric_return_items.quantity) from fabric_return_items
            left join fabric_return_invoices on fabric_return_items.invoice_id = fabric_return_invoices.id
            where fabric_return_invoices.date < '$from'
            and fabric_return_items.fabric_id = fabrics.id
            and fabric_return_items.deleted_at is null
            and fabric_return_items.status = 'confirmed'), 0) as previous_return_quantity"),

            DB::raw("COALESCE((select sum(fabric_return_items.quantity) from fabric_return_items
            left join fabric_return_invoices on fabric_return_items.invoice_id = fabric_return_invoices.id
            where fabric_return_invoices.date between '$from' and '$to'
            and fabric_return_items.fabric_id = fabrics.id
            and fabric_return_items.deleted_at is null
            and fabric_return_items.status = 'confirmed'), 0) as return_quantity"),

            // Transfers
            DB::raw("COALESCE((select sum(fabric_transfer_items.transfer_quantity) from fabric_transfer_items
            left join fabric_transfer_invoices on fabric_transfer_items.invoice_id = fabric_transfer_invoices.id
            where fabric_transfer_invoices.date < '$from'
            and fabric_transfer_items.fabric_id = fabrics.id
            and fabric_transfer_items.deleted_at is null
            and fabric_transfer_items.status = 'confirmed'), 0) as previous_transfer_quantity"),

            DB::raw("COALESCE((select sum(fabric_transfer_items.transfer_quantity) from fabric_transfer_items
            left join fabric_transfer_invoices on fabric_transfer_items.invoice_id = fabric_transfer_invoices.id
            where fabric_transfer_invoices.date between '$from' and '$to'
            and fabric_transfer_items.fabric_id = fabrics.id
            and fabric_transfer_items.deleted_at is null
            and fabric_transfer_items.status = 'confirmed'), 0) as transfer_quantity"),

            // Receives
            DB::raw("(COALESCE((select sum(fabric_transfer_receive_items.quantity) from fabric_transfer_receive_items
            where fabric_transfer_receive_items.date < '$from'
            and fabric_transfer_receive_items.fabric_id = fabrics.id
            and fabric_transfer_receive_items.deleted_at is null
            and fabric_transfer_receive_items.status = 'confirmed'), 0)) as previous_receive_quantity"),

            DB::raw("(COALESCE((select sum(fabric_transfer_receive_items.quantity) from fabric_transfer_receive_items
            where fabric_transfer_receive_items.date between '$from' and '$to'
            and fabric_transfer_receive_items.fabric_id = fabrics.id
            and fabric_transfer_receive_items.deleted_at is null
            and fabric_transfer_receive_items.status = 'confirmed'), 0)) as receive_quantity"),

            // Adjust
            DB::raw("(COALESCE((select sum(adjust_quantities.quantity) from adjust_quantities
            where adjust_quantities.created_at < '$from'
            and adjust_quantities.adjustable_id = fabrics.id
            and adjust_quantities.adjustable_type = 'App\\\Models\\\Fabric'
            and adjust_quantities.deleted_at is null), 0)) as previous_adjust_quantity"),

            DB::raw("(COALESCE((select sum(adjust_quantities.quantity) from adjust_quantities
            where adjust_quantities.created_at between '$from' and '$to'
            and adjust_quantities.adjustable_id = fabrics.id
            and adjust_quantities.adjustable_type = 'App\\\Models\\\Fabric'
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
