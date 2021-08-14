<?php

namespace App\Nova\Lenses\Material;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use App\Models\MaterialCategory;
use Illuminate\Support\Facades\DB;
use Treestoneit\TextWrap\TextWrap;
use App\Nova\Filters\CategoryFilter;
use AwesomeNova\Filters\DependentFilter;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\Lens\MaterialLocationFilter;
use App\Nova\Actions\Materials\StockSummary\DownloadPdf;
use App\Nova\Filters\Lens\MaterialSummaryDateRangeFilter;
use App\Nova\Actions\Materials\StockSummary\DownloadExcel;

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
                ->leftJoin('material_categories', 'materials.category_id', '=', 'material_categories.id')
                ->leftJoin('locations', 'materials.location_id', '=', 'locations.id')
                ->leftJoin('units', 'materials.unit_id', '=', 'units.id')
                ->where('materials.deleted_at', "=", null)
                ->groupBy('materials.id')
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
            'materials.id',
            'locations.name as location_name',
            'materials.name',
            'materials.opening_quantity',
            'units.name as unit_name',
            'material_categories.name as category_name',

            // Purchases
            DB::raw("(COALESCE((select sum(material_receive_items.quantity) from material_receive_items
            where material_receive_items.material_id = materials.id
            and material_receive_items.deleted_at is null
            and material_receive_items.status = 'confirmed'), 0)) as purchase_quantity"),

            // Distributions
            DB::raw("(COALESCE((select sum(material_distributions.quantity) from material_distributions
            where material_distributions.material_id = materials.id
            and material_distributions.status = 'confirmed'
            and material_distributions.deleted_at  is null), 0)) as distribution_quantity"),

            // Returns
            DB::raw("COALESCE((select sum(material_return_items.quantity) from material_return_items
            left join material_return_invoices on material_return_items.invoice_id = material_return_invoices.id
            where material_return_items.material_id = materials.id
            and material_return_items.deleted_at is null
            and material_return_items.status = 'confirmed'), 0) as return_quantity"),

            // Transfers
            DB::raw("COALESCE((select sum(material_transfer_items.transfer_quantity) from material_transfer_items
            left join material_transfer_invoices on material_transfer_items.invoice_id = material_transfer_invoices.id
            where material_transfer_items.material_id = materials.id
            and material_transfer_items.deleted_at is null
            and material_transfer_items.status = 'confirmed'), 0) as transfer_quantity"),

            // Transfer Receives
            DB::raw("(COALESCE((select sum(material_transfer_receive_items.quantity) from material_transfer_receive_items
            where material_transfer_receive_items.material_id = materials.id
            and material_transfer_receive_items.deleted_at is null
            and material_transfer_receive_items.status = 'confirmed'), 0)) as receive_quantity"),

            // Adjust
            DB::raw('(COALESCE((select sum(adjust_quantities.quantity) from adjust_quantities
            where adjust_quantities.adjustable_id = materials.id
            and adjust_quantities.adjustable_type = "App\\\Models\\\Material"
            and adjust_quantities.deleted_at  is null), 0)) as adjust_quantity'),

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

            TextWrap::make('Category', 'category_name')
                ->sortable()
                ->wrapMethod('length', 20),

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
            MaterialLocationFilter::make('Location', 'location_id')->canSee(function ($request) {
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
            }),

            DependentFilter::make('Category', 'category_id')
                ->dependentOf('location_id')
                ->withOptions(function (Request $request, $filters) {
                    return MaterialCategory::where('location_id', $filters['location_id'])
                        ->pluck('name', 'id');
                })->canSee(function ($request) {
                    return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
                }),


            (new CategoryFilter)->canSee(function ($request) {
                return !$request->user()->isSuperAdmin() || !$request->user()->hasPermissionTo('view any locations data');
            }),

            new MaterialSummaryDateRangeFilter(),

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
                    return ($request->user()->hasPermissionTo('can download materials') || $request->user()->isSuperAdmin());
                })
                ->canRun(function ($request) {
                    return ($request->user()->hasPermissionTo('can download materials') || $request->user()->isSuperAdmin());
                })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download pdf?")
                ->withWriterType(\Maatwebsite\Excel\Excel::MPDF)
                ->withFilename('materials_stock_summary.pdf'),

            (new DownloadExcel)->withHeadings('#', 'Location', 'Category', 'Name', 'Previous', 'Purchase', 'Distribution', 'Return', 'Transfer', 'Receive', 'Adjust', 'Remaining')
                ->canSee(function ($request) {
                    return ($request->user()->hasPermissionTo('can download materials') || $request->user()->isSuperAdmin());
                })
                ->canRun(function ($request) {
                    return ($request->user()->hasPermissionTo('can download materials') || $request->user()->isSuperAdmin());
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
        return 'material-stock-summary';
    }
}