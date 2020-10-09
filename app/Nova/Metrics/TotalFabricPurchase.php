<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Nova;
use Laravel\Nova\Metrics\Value;
use App\Models\FabricPurchaseOrder;
use Laravel\Nova\Http\Requests\NovaRequest;

class TotalFabricPurchase extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
         // Query for superadmin
         if($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data')){
            return $this->sum($request, FabricPurchaseOrder::class, 'total_purchase_amount', 'date');
        }

        // Query for users
        return $this->sum($request, FabricPurchaseOrder::query()->where('location_id', $request->user()->locationId), 'total_purchase_amount', 'date');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => __('30 Days'),
            60 => __('60 Days'),
            365 => __('365 Days'),
            'TODAY' => __('Today'),
            'MTD' => __('Month To Date'),
            'QTD' => __('Quarter To Date'),
            'YTD' => __('Year To Date'),
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'total-fabric-purchase';
    }
}
