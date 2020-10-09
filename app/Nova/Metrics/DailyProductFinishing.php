<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Metrics\Trend;
use App\Models\FinishingInvoice;
use Laravel\Nova\Http\Requests\NovaRequest;

class DailyProductFinishing extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        if($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data')){

            return $this->sumByDays($request, FinishingInvoice::class, 'total_amount');
        }
        return $this->sumByDays($request, FinishingInvoice::query()->where('location_id', $request->user()->locationId), 'total_amount');
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
            90 => __('90 Days'),
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
        return 'daily-product-finishing';
    }
}
