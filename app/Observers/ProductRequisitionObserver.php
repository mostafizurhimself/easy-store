<?php

namespace App\Observers;

use App\Models\ProductRequisition;

class ProductRequisitionObserver
{
    /**
     * Handle the product requisition "created" event.
     *
     * @param  \App\Models\ProductRequisition  $productRequisition
     * @return void
     */
    public function created(ProductRequisition $productRequisition)
    {
        //
    }

    /**
     * Handle the product requisition "updating" event.
     *
     * @param  \App\Models\ProductRequisition  $productRequisition
     * @return void
     */
    public function updating(ProductRequisition $productRequisition)
    {
        if ($productRequisition->isDirty('receiver_id')) {
            $productRequisition->requisitionItems()->forceDelete();
        }
    }

    /**
     * Handle the product requisition "deleting" event.
     *
     * @param  \App\Models\ProductRequisition  $productRequisition
     * @return void
     */
    public function deleting(ProductRequisition $productRequisition)
    {
        if ($productRequisition->isForceDeleting()) {
            $productRequisition->requisitionItems()->forceDelete();
        } else {
            $productRequisition->requisitionItems()->delete();
        }
    }

    /**
     * Handle the product requisition "restored" event.
     *
     * @param  \App\Models\ProductRequisition  $productRequisition
     * @return void
     */
    public function restored(ProductRequisition $productRequisition)
    {
        $productRequisition->requisitionItems()->restore();
    }

    /**
     * Handle the product requisition "force deleted" event.
     *
     * @param  \App\Models\ProductRequisition  $productRequisition
     * @return void
     */
    public function forceDeleted(ProductRequisition $productRequisition)
    {
        //
    }
}
