<?php

namespace App\Nova\Lenses\Fabric;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\Number;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\BelongsTo;
use Treestoneit\TextWrap\TextWrap;
use App\Nova\Filters\LocationFilter;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
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

            TextWrap::make('Name', 'name')
                ->sortable()
                ->wrapMethod('length', 30),

            Text::make('Previous', function () {
                if (isset($this->purchase_quantity)) {
                    $this->previous_quantity = ($this->opening_quantity + $this->previous_purchase_quantity + $this->previous_receive_quantity) - ($this->previous_distribution_quantity + $this->previous_return_quantity + $this->previous_transfer_quantity) +
                        $this->previous_adjust_quantity;
                    return $this->previous_quantity . " " . $this->unit_name;
                }
                return 0;
            })
                ->sortable(),

            Text::make('Purchase', function () {
                if (isset($this->purchase_quantity)) {
                    return $this->purchase_quantity . " " . $this->unit_name;
                }
                return 0;
            })
                ->sortable(),

            Text::make('Distribution', function () {
                if (isset($this->distribution_quantity)) {
                    return $this->distribution_quantity . " " . $this->unit_name;
                }
                return 0;
            })
                ->sortable(),

            Text::make('Return', function () {
                if (isset($this->return_quantity)) {
                    return $this->return_quantity . " " . $this->unit_name;
                }
                return 0;
            })
                ->sortable(),

            Text::make('Transfer', function () {
                if (isset($this->transfer_quantity)) {
                    return $this->transfer_quantity . " " . $this->unit_name;
                }
                return 0;
            })
                ->sortable(),

            Text::make('Receive', function () {
                if (isset($this->receive_quantity)) {
                    return $this->receive_quantity . " " . $this->unit_name;
                }
                return 0;
            })
                ->sortable(),

            Text::make('Adjust', function () {
                if (isset($this->adjust_quantity)) {
                    return $this->adjust_quantity . " " . $this->unit_name;
                }
                return 0;
            })
                ->sortable(),

            Text::make('Remaining', function () {
                if (isset($this->previous_quantity)) {
                    $this->remaining_quantity = ($this->previous_quantity + $this->purchase_quantity + $this->receive_quantity) - ($this->distribution_quantity + $this->return_quantity + $this->transfer_quantity)
                        + $this->adjust_quantity;
                    return $this->remaining_quantity . " " . $this->unit_name;
                }
                return 0;
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
            LocationFilter::make('Location', 'location_id')->canSee(function ($request) {
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
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
            (new DownloadPdf)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download fabrics') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download fabrics') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download pdf?"),

            (new DownloadExcel)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download fabrics') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
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
