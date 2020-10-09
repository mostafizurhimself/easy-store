<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Nova;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Http\Requests\NovaRequest;

class TotalPurchaseOrder extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $timezone = Nova::resolveUserTimezone($request) ?? $request->timezone;

        if($request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data'))
        {

            $previousTotalFabricPurchase = \App\Models\FabricPurchaseOrder::whereBetween('date',$this->previousRange($request->range, $timezone))->sum('total_purchase_amount');
            $previousTotalMaterialPurchase = \App\Models\MaterialPurchaseOrder::whereBetween('date',$this->previousRange($request->range, $timezone))->sum('total_purchase_amount');
            $previousTotalAssetPurchase = \App\Models\AssetPurchaseOrder::whereBetween('date',$this->previousRange($request->range, $timezone))->sum('total_purchase_amount');

            $totalFabricPurchase = \App\Models\FabricPurchaseOrder::whereBetween('date',$this->currentRange($request->range, $timezone))->sum('total_purchase_amount');
            $totalMaterialPurchase = \App\Models\MaterialPurchaseOrder::whereBetween('date',$this->currentRange($request->range, $timezone))->sum('total_purchase_amount');
            $totalAssetPurchase = \App\Models\AssetPurchaseOrder::whereBetween('date',$this->currentRange($request->range, $timezone))->sum('total_purchase_amount');

            $previousValue = $previousTotalFabricPurchase + $previousTotalMaterialPurchase + $previousTotalAssetPurchase;
            $value = $totalFabricPurchase + $totalMaterialPurchase + $totalAssetPurchase;
        }else{

            $previousTotalFabricPurchase = \App\Models\FabricPurchaseOrder::where('location_id', $request->user()->locationId)->whereBetween('date',$this->previousRange($request->range, $timezone))->sum('total_purchase_amount');
            $previousTotalMaterialPurchase = \App\Models\MaterialPurchaseOrder::where('location_id', $request->user()->locationId)->whereBetween('date',$this->previousRange($request->range, $timezone))->sum('total_purchase_amount');
            $previousTotalAssetPurchase = \App\Models\AssetPurchaseOrder::where('location_id', $request->user()->locationId)->whereBetween('date',$this->previousRange($request->range, $timezone))->sum('total_purchase_amount');

            $totalFabricPurchase = \App\Models\FabricPurchaseOrder::where('location_id', $request->user()->locationId)->whereBetween('date',$this->currentRange($request->range, $timezone))->sum('total_purchase_amount');
            $totalMaterialPurchase = \App\Models\MaterialPurchaseOrder::where('location_id', $request->user()->locationId)->whereBetween('date',$this->currentRange($request->range, $timezone))->sum('total_purchase_amount');
            $totalAssetPurchase = \App\Models\AssetPurchaseOrder::where('location_id', $request->user()->locationId)->whereBetween('date',$this->currentRange($request->range, $timezone))->sum('total_purchase_amount');

            $previousValue = $previousTotalFabricPurchase + $previousTotalMaterialPurchase + $previousTotalAssetPurchase;
            $value = $totalFabricPurchase + $totalMaterialPurchase + $totalAssetPurchase;
        }


        return $this->result($value)->previous($previousValue);
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
        return 'total-purchase-order';
    }
}
