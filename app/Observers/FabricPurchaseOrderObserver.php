<?php

namespace App\Observers;

use App\Models\FabricPurchaseOrder;

class FabricPurchaseOrderObserver
{
    /**
     * Handle the fabric purchase order "created" event.
     *
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return void
     */
    public function created(FabricPurchaseOrder $fabricPurchaseOrder)
    {
        //
    }

    /**
     * Handle the fabric purchase order "updating" event.
     *
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return void
     */
    public function updating(FabricPurchaseOrder $fabricPurchaseOrder)
    {
        if($fabricPurchaseOrder->isDirty('location_id') || $fabricPurchaseOrder->isDirty('supplier_id')){
            $fabricPurchaseOrder->purchaseItems()->forceDelete();
        }
    }

    /**
     * Handle the fabric purchase order "deleted" event.
     *
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return void
     */
    public function deleted(FabricPurchaseOrder $fabricPurchaseOrder)
    {
        //
    }

    /**
     * Handle the fabric purchase order "restored" event.
     *
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return void
     */
    public function restored(FabricPurchaseOrder $fabricPurchaseOrder)
    {
        //
    }

    /**
     * Handle the fabric purchase order "force deleted" event.
     *
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return void
     */
    public function forceDeleted(FabricPurchaseOrder $fabricPurchaseOrder)
    {
        //
    }
}
