<?php

namespace App\Nova\Lenses\Fabric;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use App\Models\FabricCategory;
use Illuminate\Support\Facades\DB;
use Treestoneit\TextWrap\TextWrap;
use App\Nova\Filters\CategoryFilter;
use AwesomeNova\Filters\DependentFilter;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\Lens\FabricLocationFilter;
use App\Nova\Actions\Fabrics\StockSummary\DownloadPdf;
use App\Nova\Filters\Lens\FabricSummaryDateRangeFilter;
use App\Nova\Actions\Fabrics\StockSummary\DownloadExcel;

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
                ->leftJoin('fabric_categories', 'fabrics.category_id', '=', 'fabric_categories.id')
                ->leftJoin('locations', 'fabrics.location_id', '=', 'locations.id')
                ->leftJoin('units', 'fabrics.unit_id', '=', 'units.id')
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
            'fabrics.opening_quantity',
            'units.name as unit_name',
            'fabric_categories.name as category_name',

            // Purchases
            DB::raw("(COALESCE((select sum(fabric_receive_items.quantity) from fabric_receive_items
            where fabric_receive_items.fabric_id = fabrics.id
            and fabric_receive_items.deleted_at is null
            and fabric_receive_items.status = 'confirmed'), 0)) as purchase_quantity"),

            // Distributions
            DB::raw("(COALESCE((select sum(fabric_distributions.quantity) from fabric_distributions
            where fabric_distributions.fabric_id = fabrics.id
            and fabric_distributions.status = 'confirmed'
            and fabric_distributions.deleted_at  is null), 0)) as distribution_quantity"),

            // Returns
            DB::raw("COALESCE((select sum(fabric_return_items.quantity) from fabric_return_items
            left join fabric_return_invoices on fabric_return_items.invoice_id = fabric_return_invoices.id
            where fabric_return_items.fabric_id = fabrics.id
            and fabric_return_items.deleted_at is null
            and fabric_return_items.status = 'confirmed'), 0) as return_quantity"),

            // Transfers
            DB::raw("COALESCE((select sum(fabric_transfer_items.transfer_quantity) from fabric_transfer_items
            left join fabric_transfer_invoices on fabric_transfer_items.invoice_id = fabric_transfer_invoices.id
            where fabric_transfer_items.fabric_id = fabrics.id
            and fabric_transfer_items.deleted_at is null
            and fabric_transfer_items.status = 'confirmed'), 0) as transfer_quantity"),

            // Transfer Receives
            DB::raw("(COALESCE((select sum(fabric_transfer_receive_items.quantity) from fabric_transfer_receive_items
            where fabric_transfer_receive_items.fabric_id = fabrics.id
            and fabric_transfer_receive_items.deleted_at is null
            and fabric_transfer_receive_items.status = 'confirmed'), 0)) as receive_quantity"),

            // Adjust
            DB::raw("(COALESCE((select sum(adjust_quantities.quantity) from adjust_quantities
            where adjust_quantities.adjustable_id = fabrics.id
            and adjust_quantities.adjustable_type = 'App\\\Models\\\Fabric'
            and adjust_quantities.deleted_at  is null), 0)) as adjust_quantity"),

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

            Text::make('Category', 'category_name')
                ->sortable(),

            TextWrap::make('Name', 'name')
                ->sortable()
                ->wrapMethod('length', 30),

            Text::make('Previous', function () {
                if (isset($this->previous_purchase_quantity) && isset($this->previous_distribution_quantity)) {
                    $this->previous_quantity = ($this->opening_quantity + $this->previous_purchase_quantity + $this->previous_receive_quantity) - ($this->previous_distribution_quantity + $this->previous_return_quantity + $this->previous_transfer_quantity) +
                        $this->previous_adjust_quantity;
                } else {
                    $this->previous_quantity = $this->opening_quantity;
                }
                return $this->previous_quantity . " " . $this->unit_name;
            })
                ->sortable(),

            Text::make('Purchase', function () {
                if (isset($this->purchase_quantity)) {
                    return $this->purchase_quantity . " " . $this->unit_name;
                }
                return "N/A";
            })
                ->sortable(),

            Text::make('Distribution', function () {
                if (isset($this->distribution_quantity)) {
                    return $this->distribution_quantity . " " . $this->unit_name;
                }
                return "N/A";
            })
                ->sortable(),

            Text::make('Return', function () {
                if (isset($this->return_quantity)) {
                    return $this->return_quantity . " " . $this->unit_name;
                }
                return "N/A";
            })
                ->sortable(),

            Text::make('Transfer', function () {
                if (isset($this->transfer_quantity)) {
                    return $this->transfer_quantity . " " . $this->unit_name;
                }
                return "N/A";
            })
                ->sortable(),

            Text::make('Receive', function () {
                if (isset($this->receive_quantity)) {
                    return $this->receive_quantity . " " . $this->unit_name;
                }
                return "N/A";
            })
                ->sortable(),

            Text::make('Adjust', function () {
                if (isset($this->adjust_quantity)) {
                    return $this->adjust_quantity . " " . $this->unit_name;
                }
                return "N/A";
            })
                ->sortable(),

            Text::make('Remaining', function () {
                if (isset($this->previous_quantity)) {
                    $this->remaining_quantity = ($this->previous_quantity + $this->purchase_quantity + $this->receive_quantity) - ($this->distribution_quantity + $this->return_quantity + $this->transfer_quantity)
                        + $this->adjust_quantity;
                    return $this->remaining_quantity . " " . $this->unit_name;
                }
                return "N/A";
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
            FabricLocationFilter::make('Location', 'location_id')->canSee(function ($request) {
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
            }),

            DependentFilter::make('Category', 'category_id')
                ->dependentOf('location_id')
                ->withOptions(function (Request $request, $filters) {
                    return FabricCategory::where('location_id', $filters['location_id'])
                        ->pluck('name', 'id');
                })->canSee(function ($request) {
                    return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
                }),


            (new CategoryFilter)->canSee(function ($request) {
                return !$request->user()->isSuperAdmin() || !$request->user()->hasPermissionTo('view any locations data');
            }),

            new FabricSummaryDateRangeFilter(),

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
        return [
            (new DownloadPdf)->withHeadings('#', 'Location', 'Category', 'Name', 'Previous', 'Purchase', 'Distribution', 'Return', 'Transfer', 'Receive', 'Adjust', 'Remaining')
                ->canSee(function ($request) {
                    return ($request->user()->hasPermissionTo('can download fabrics') || $request->user()->isSuperAdmin());
                })
                ->canRun(function ($request) {
                    return ($request->user()->hasPermissionTo('can download fabrics') || $request->user()->isSuperAdmin());
                })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download pdf?")
                ->withWriterType(\Maatwebsite\Excel\Excel::MPDF)
                ->withFilename('fabrics_stock_summary.pdf'),

            (new DownloadExcel)->withHeadings('#', 'Location', 'Category', 'Name', 'Previous', 'Purchase', 'Distribution', 'Return', 'Transfer', 'Receive', 'Adjust', 'Remaining')
                ->canSee(function ($request) {
                    return ($request->user()->hasPermissionTo('can download fabrics') || $request->user()->isSuperAdmin());
                })
                ->canRun(function ($request) {
                    return ($request->user()->hasPermissionTo('can download fabrics') || $request->user()->isSuperAdmin());
                })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download excel?"),
        ];
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