<?php

namespace App\Observers;

use App\Models\Models\AssetPurchaseOrder;

class AssetPurchaseOrderObserver
{
    /**
     * Handle the asset purchase order "created" event.
     *
     * @param  \App\Models\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function created(AssetPurchaseOrder $assetPurchaseOrder)
    {
        //
    }

    /**
     * Handle the asset purchase order "updated" event.
     *
     * @param  \App\Models\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function updated(AssetPurchaseOrder $assetPurchaseOrder)
    {
        //
    }

    /**
     * Handle the asset purchase order "deleted" event.
     *
     * @param  \App\Models\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function deleted(AssetPurchaseOrder $assetPurchaseOrder)
    {
        //
    }

    /**
     * Handle the asset purchase order "restored" event.
     *
     * @param  \App\Models\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function restored(AssetPurchaseOrder $assetPurchaseOrder)
    {
        //
    }

    /**
     * Handle the asset purchase order "force deleted" event.
     *
     * @param  \App\Models\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function forceDeleted(AssetPurchaseOrder $assetPurchaseOrder)
    {
        //
    }
}
