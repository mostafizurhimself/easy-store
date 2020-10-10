<?php

namespace App\Observers;

use App\Models\MaterialReceiveItem;
use App\Models\MaterialPurchaseItem;

class MaterialReceiveItemObserver
{

    /**
     * Handle the material receive item "saving" event.
     *
     * @param  \App\Models\MaterialReceiveItem  $materialReceiveItem
     * @return void
     */
    public function saving(MaterialReceiveItem $materialReceiveItem)
    {
        //Get the related purchase
        $purchaseItem = MaterialPurchaseItem::find($materialReceiveItem->purchaseItemId);

        //Set purchase id
        $materialReceiveItem->purchaseOrderId = $purchaseItem->purchaseOrderId;
        //Set material id
        $materialReceiveItem->materialId = $purchaseItem->materialId;
        //Set rate
        if(empty($materialReceiveItem->rate)){
            $materialReceiveItem->rate = $purchaseItem->purchaseRate;
        }
        if(empty($materialReceiveItem->unitId)){
            $materialReceiveItem->unitId = $purchaseItem->material->unitId;
        }
        //Set Amount
        $materialReceiveItem->amount = $purchaseItem->purchaseRate * $materialReceiveItem->quantity;
    }

    /**
     * Handle the material receive item "saved" event.
     *
     * @param  \App\Models\MaterialReceiveItem  $materialReceiveItem
     * @return void
     */
    public function saved(MaterialReceiveItem $materialReceiveItem)
    {
        // Update the purchase total Receive Amount
        $materialReceiveItem->purchaseOrder->updateReceiveAmount();

        //Update the purchase item receive quantity
        $materialReceiveItem->purchaseItem->updateReceiveQuantity();

        //Update the purchase item receive amount
        $materialReceiveItem->purchaseItem->updateReceiveAmount();

        //Change the purchase item status
        $materialReceiveItem->purchaseItem->updateStatus();

        //Change the purchase status
        $materialReceiveItem->purchaseOrder->updateStatus();
    }

    /**
     * Handle the material receive item "deleted" event.
     *
     * @param  \App\Models\MaterialReceiveItem  $materialReceiveItem
     * @return void
     */
    public function deleted(MaterialReceiveItem $materialReceiveItem)
    {
        // Update the purchase total Receive Amount
        $materialReceiveItem->purchaseOrder->updateReceiveAmount();

        //Update the purchase item receive quantity
        $materialReceiveItem->purchaseItem->updateReceiveQuantity();

        //Update the purchase item receive amount
        $materialReceiveItem->purchaseItem->updateReceiveAmount();

        //Change the purchase item status
        $materialReceiveItem->purchaseItem->updateStatus();

        //Change the purchase status
        $materialReceiveItem->purchaseOrder->updateStatus();
    }

    /**
     * Handle the material receive item "restored" event.
     *
     * @param  \App\Models\MaterialReceiveItem  $materialReceiveItem
     * @return void
     */
    public function restored(MaterialReceiveItem $materialReceiveItem)
    {
        // Update the purchase total Receive Amount
        $materialReceiveItem->purchaseOrder->updateReceiveAmount();

        //Update the purchase item receive quantity
        $materialReceiveItem->purchaseItem->updateReceiveQuantity();

        //Update the purchase item receive amount
        $materialReceiveItem->purchaseItem->updateReceiveAmount();

        //Change the purchase item status
        $materialReceiveItem->purchaseItem->updateStatus();

        //Change the purchase status
        $materialReceiveItem->purchaseOrder->updateStatus();
    }

    /**
     * Handle the material receive item "force deleted" event.
     *
     * @param  \App\Models\MaterialReceiveItem  $materialReceiveItem
     * @return void
     */
    public function forceDeleted(MaterialReceiveItem $materialReceiveItem)
    {
        // Update the purchase total Receive Amount
        $materialReceiveItem->purchaseOrder->updateReceiveAmount();

        //Update the purchase item receive quantity
        $materialReceiveItem->purchaseItem->updateReceiveQuantity();

        //Update the purchase item receive amount
        $materialReceiveItem->purchaseItem->updateReceiveAmount();

        //Change the purchase item status
        $materialReceiveItem->purchaseItem->updateStatus();

        //Change the purchase status
        $materialReceiveItem->purchaseOrder->updateStatus();
    }
}
