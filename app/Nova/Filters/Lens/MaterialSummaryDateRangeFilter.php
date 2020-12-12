<?php

namespace App\Nova\Filters\Lens;

use Carbon\Carbon;
use App\Traits\ActiveScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ampeco\Filters\DateRangeFilter;

class MaterialSummaryDateRangeFilter extends DateRangeFilter
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
            'materials.id',
            'locations.name as location_name',
            'materials.name',
            'materials.opening_quantity',
            'material_categories.name as category_name',

            // Purchases
            DB::raw("(COALESCE((select sum(material_receive_items.quantity) from material_receive_items
            where material_receive_items.date < '$from'
            and material_receive_items.material_id = materials.id
            and material_receive_items.deleted_at is null
            and material_receive_items.status = 'confirmed'), 0)) as previous_purchase_quantity"),

            DB::raw("(COALESCE((select sum(material_receive_items.quantity) from material_receive_items
            where material_receive_items.date between '$from' and '$to'
            and material_receive_items.material_id = materials.id
            and material_receive_items.deleted_at is null
            and material_receive_items.status = 'confirmed'), 0)) as purchase_quantity"),

            // Distributions
            DB::raw("(COALESCE((select sum(material_distributions.quantity) from material_distributions
            where material_distributions.created_at < '$from'
            and material_distributions.material_id = materials.id
            and material_distributions.status = 'confirmed'
            and material_distributions.deleted_at is null), 0)) as previous_distribution_quantity"),

            DB::raw("(COALESCE((select sum(material_distributions.quantity) from material_distributions
            where material_distributions.created_at between '$from' and '$to'
            and material_distributions.material_id = materials.id
            and material_distributions.status = 'confirmed'
            and material_distributions.deleted_at  is null), 0)) as distribution_quantity"),

            // Returns
            DB::raw("COALESCE((select sum(material_return_items.quantity) from material_return_items
            left join material_return_invoices on material_return_items.invoice_id = material_return_invoices.id
            where material_return_invoices.date < '$from'
            and material_return_items.material_id = materials.id
            and material_return_items.deleted_at is null
            and material_return_items.status = 'confirmed'), 0) as previous_return_quantity"),

            DB::raw("COALESCE((select sum(material_return_items.quantity) from material_return_items
            left join material_return_invoices on material_return_items.invoice_id = material_return_invoices.id
            where material_return_invoices.date between '$from' and '$to'
            and material_return_items.material_id = materials.id
            and material_return_items.deleted_at is null
            and material_return_items.status = 'confirmed'), 0) as return_quantity"),

            // Transfers
            DB::raw("COALESCE((select sum(material_transfer_items.transfer_quantity) from material_transfer_items
            left join material_transfer_invoices on material_transfer_items.invoice_id = material_transfer_invoices.id
            where material_transfer_invoices.date < '$from'
            and material_transfer_items.material_id = materials.id
            and material_transfer_items.deleted_at is null
            and material_transfer_items.status = 'confirmed'), 0) as previous_transfer_quantity"),

            DB::raw("COALESCE((select sum(material_transfer_items.transfer_quantity) from material_transfer_items
            left join material_transfer_invoices on material_transfer_items.invoice_id = material_transfer_invoices.id
            where material_transfer_invoices.date between '$from' and '$to'
            and material_transfer_items.material_id = materials.id
            and material_transfer_items.deleted_at is null
            and material_transfer_items.status = 'confirmed'), 0) as transfer_quantity"),

            // Receives
            DB::raw("(COALESCE((select sum(material_transfer_receive_items.quantity) from material_transfer_receive_items
            where material_transfer_receive_items.date < '$from'
            and material_transfer_receive_items.material_id = materials.id
            and material_transfer_receive_items.deleted_at is null
            and material_transfer_receive_items.status = 'confirmed'), 0)) as previous_receive_quantity"),

            DB::raw("(COALESCE((select sum(material_transfer_receive_items.quantity) from material_transfer_receive_items
            where material_transfer_receive_items.date between '$from' and '$to'
            and material_transfer_receive_items.material_id = materials.id
            and material_transfer_receive_items.deleted_at is null
            and material_transfer_receive_items.status = 'confirmed'), 0)) as receive_quantity"),

            // Adjust
            DB::raw("(COALESCE((select sum(adjust_quantities.quantity) from adjust_quantities
            where adjust_quantities.created_at < '$from'
            and adjust_quantities.adjustable_id = materials.id
            and adjust_quantities.adjustable_type = 'App\\\Models\\\material'
            and adjust_quantities.deleted_at is null), 0)) as previous_adjust_quantity"),

            DB::raw("(COALESCE((select sum(adjust_quantities.quantity) from adjust_quantities
            where adjust_quantities.created_at between '$from' and '$to'
            and adjust_quantities.adjustable_id = materials.id
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
