<?php

namespace App\Nova\Lenses\Asset;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Models\AssetCategory;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use Illuminate\Support\Facades\DB;
use Treestoneit\TextWrap\TextWrap;
use App\Nova\Filters\CategoryFilter;
use AwesomeNova\Filters\DependentFilter;
use App\Nova\Filters\AssetLocationFilter;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\Assets\StockSummary\DownloadPdf;
use App\Nova\Filters\Lens\AssetSummaryDateRangeFilter;
use App\Nova\Actions\Assets\StockSummary\DownloadExcel;

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
                ->leftJoin('asset_categories', 'assets.category_id', '=', 'asset_categories.id')
                ->leftJoin('locations', 'assets.location_id', '=', 'locations.id')
                ->leftJoin('units', 'assets.unit_id', '=', 'units.id')
                ->where('assets.deleted_at', "=", null)
                ->groupBy('assets.id')
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
            'assets.id',
            'locations.name as location_name',
            'assets.name',
            'assets.opening_quantity',
            'units.name as unit_name',
            'asset_categories.name as category_name',

            // Purchases
            DB::raw("(COALESCE((select sum(asset_receive_items.quantity) from asset_receive_items
            where asset_receive_items.asset_id = assets.id
            and asset_receive_items.deleted_at is null
            and asset_receive_items.status = 'confirmed'), 0)) as purchase_quantity"),

            // Consume
            DB::raw("(COALESCE((select sum(asset_consumes.quantity) from asset_consumes
            where asset_consumes.asset_id = assets.id
            and asset_consumes.deleted_at  is null), 0)) as consume_quantity"),

            // Returns
            DB::raw("COALESCE((select sum(asset_return_items.quantity) from asset_return_items
            left join asset_return_invoices on asset_return_items.invoice_id = asset_return_invoices.id
            where asset_return_items.asset_id = assets.id
            and asset_return_items.deleted_at is null
            and asset_return_items.status = 'confirmed'), 0) as return_quantity"),

            // Distribution
            DB::raw("COALESCE((select sum(asset_distribution_items.distribution_quantity) from asset_distribution_items
            left join asset_distribution_invoices on asset_distribution_items.invoice_id = asset_distribution_invoices.id
            where asset_distribution_items.asset_id = assets.id
            and asset_distribution_items.deleted_at is null
            and asset_distribution_items.status != 'draft'), 0) as distribution_quantity"),

            // Distribution Receives
            DB::raw("(COALESCE((select sum(asset_distribution_receive_items.quantity) from asset_distribution_receive_items
            where asset_distribution_receive_items.asset_id = assets.id
            and asset_distribution_receive_items.deleted_at is null
            and asset_distribution_receive_items.status = 'confirmed'), 0)) as receive_quantity"),

            // Adjust
            DB::raw("(COALESCE((select sum(adjust_quantities.quantity) from adjust_quantities
            where adjust_quantities.adjustable_id = assets.id
            and adjust_quantities.adjustable_type = 'App\\\Models\\\Asset'
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
                    $this->previous_quantity = ($this->opening_quantity + $this->previous_purchase_quantity + $this->previous_receive_quantity) - ($this->previous_consume_quantity + $this->previous_return_quantity + $this->previous_distribution_quantity) +
                        $this->previous_adjust_quantity;
                }else{
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

            Text::make('Consume', function () {
                if (isset($this->consume_quantity)) {
                    return $this->consume_quantity . " " . $this->unit_name;
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

            Text::make('Distribution', function () {
                if (isset($this->distribution_quantity)) {
                    return $this->distribution_quantity . " " . $this->unit_name;
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
                    $this->remaining_quantity = ($this->previous_quantity + $this->purchase_quantity + $this->receive_quantity) - ($this->consume_quantity + $this->return_quantity + $this->distribution_quantity)
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
            AssetLocationFilter::make('Location', 'location_id')->canSee(function ($request) {
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
            }),

            DependentFilter::make('Category', 'category_id')
                ->dependentOf('location_id')
                ->withOptions(function (Request $request, $filters) {
                    return AssetCategory::where('location_id', $filters['location_id'])
                        ->pluck('name', 'id');
                })->canSee(function ($request) {
                    return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
                }),


            (new CategoryFilter)->canSee(function ($request) {
                return !$request->user()->isSuperAdmin() || !$request->user()->hasPermissionTo('view any locations data');
            }),

            new AssetSummaryDateRangeFilter(),

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
            (new DownloadPdf)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download assets') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download assets') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download pdf?"),

            (new DownloadExcel)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download assets') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download assets') || $request->user()->isSuperAdmin());
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
        return 'asset-stock-summary';
    }
}
