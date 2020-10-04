<?php

namespace App\Observers;

use App\Models\AssetPurchaseOrder;

class AssetPurchaseOrderObserver
{
    /**
     * Handle the asset purchase order "created" event.
     *
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function created(AssetPurchaseOrder $assetPurchaseOrder)
    {
        //
    }

    /**
     * Handle the asset purchase order "updating" event.
     *
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function updating(AssetPurchaseOrder $assetPurchaseOrder)
    {
        if($assetPurchaseOrder->isDirty('location_id') || $assetPurchaseOrder->isDirty('supplier_id')){
            $assetPurchaseOrder->purchaseItems()->forceDelete();
        }
    }

    /**
     * Handle the asset purchase order "deleted" event.
     *
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function deleted(AssetPurchaseOrder $assetPurchaseOrder)
    {
        //
    }

    /**
     * Handle the asset purchase order "restored" event.
     *
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function restored(AssetPurchaseOrder $assetPurchaseOrder)
    {
        //
    }

    /**
     * Handle the asset purchase order "force deleted" event.
     *
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function forceDeleted(AssetPurchaseOrder $assetPurchaseOrder)
    {
        //
    }
}
