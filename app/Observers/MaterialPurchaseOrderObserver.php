<?php

namespace App\Observers;

use App\Models\MaterialPurchaseOrder;

class MaterialPurchaseOrderObserver
{
    /**
     * Handle the material purchase order "created" event.
     *
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return void
     */
    public function created(MaterialPurchaseOrder $materialPurchaseOrder)
    {
        //
    }

    /**
     * Handle the material purchase order "updating" event.
     *
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return void
     */
    public function updating(MaterialPurchaseOrder $materialPurchaseOrder)
    {
        if($materialPurchaseOrder->isDirty('location_id') || $materialPurchaseOrder->isDirty('supplier_id')){
            $materialPurchaseOrder->purchaseItems()->forceDelete();
        }
    }

    /**
     * Handle the material purchase order "deleted" event.
     *
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return void
     */
    public function deleted(MaterialPurchaseOrder $materialPurchaseOrder)
    {
        //
    }

    /**
     * Handle the material purchase order "restored" event.
     *
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return void
     */
    public function restored(MaterialPurchaseOrder $materialPurchaseOrder)
    {
        //
    }

    /**
     * Handle the material purchase order "force deleted" event.
     *
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return void
     */
    public function forceDeleted(MaterialPurchaseOrder $materialPurchaseOrder)
    {
        //
    }
}
