<?php

namespace App\Observers;

use Exception;
use App\Models\FabricReceiveItem;
use App\Models\FabricPurchaseItem;

class FabricReceiveItemObserver
{

    /**
     * Handle the receive item fabric "saving" event.
     *
     * @param  \App\Models\FabricReceiveItem  $fabricReceiveItem
     * @return void
     */
    public function saving(FabricReceiveItem $fabricReceiveItem)
    {
        //Get the related purchase
        $purchaseItem = FabricPurchaseItem::find($fabricReceiveItem->purchaseItemId);

        //Set purchase id
        $fabricReceiveItem->purchaseOrderId = $purchaseItem->purchaseOrderId;
        //Set fabric id
        $fabricReceiveItem->fabricId = $purchaseItem->fabricId;
        //Set rate
        if (empty($fabricReceiveItem->rate)) {
            $fabricReceiveItem->rate = $purchaseItem->purchaseRate;
        }
        if (empty($fabricReceiveItem->unitId)) {
            $fabricReceiveItem->unitId = $purchaseItem->fabric->unitId;
        }
        //Set Amount
        $fabricReceiveItem->amount = $fabricReceiveItem->rate * $fabricReceiveItem->quantity;
    }

    /**
     * Handle the receive item fabric "saved" event.
     *
     * @param  \App\Models\FabricReceiveItem  $fabricReceiveItem
     * @return void
     */
    public function saved(FabricReceiveItem $fabricReceiveItem)
    {
        // Update the purchase total Receive Amount
        $fabricReceiveItem->purchaseOrder->updateReceiveAmount();

        //Update the purchase item receive quantity
        $fabricReceiveItem->purchaseItem->updateReceiveQuantity();

        //Update the purchase item receive amount
        $fabricReceiveItem->purchaseItem->updateReceiveAmount();

        //Change the purchase item status
        $fabricReceiveItem->purchaseItem->updateStatus();

        //Change the purchase status
        $fabricReceiveItem->purchaseOrder->updateStatus();
    }

    /**
     * Handle the receive item fabric "deleted" event.
     *
     * @param  \App\Models\FabricReceiveItem  $fabricReceiveItem
     * @return void
     */
    public function deleted(FabricReceiveItem $fabricReceiveItem)
    {
        // Update the purchase total Receive Amount
        $fabricReceiveItem->purchaseOrder->updateReceiveAmount();

        //Update the purchase item receive quantity
        $fabricReceiveItem->purchaseItem->updateReceiveQuantity();

        //Update the purchase item receive amount
        $fabricReceiveItem->purchaseItem->updateReceiveAmount();

        //Change the purchase item status
        $fabricReceiveItem->purchaseItem->updateStatus();

        //Change the purchase status
        $fabricReceiveItem->purchaseOrder->updateStatus();
    }

    /**
     * Handle the receive item fabric "restored" event.
     *
     * @param  \App\Models\FabricReceiveItem  $fabricReceiveItem
     * @return void
     */
    public function restored(FabricReceiveItem $fabricReceiveItem)
    {
        // Update the purchase total Receive Amount
        $fabricReceiveItem->purchaseOrder->updateReceiveAmount();

        //Update the purchase item receive quantity
        $fabricReceiveItem->purchaseItem->updateReceiveQuantity();

        //Update the purchase item receive amount
        $fabricReceiveItem->purchaseItem->updateReceiveAmount();

        //Change the purchase item status
        $fabricReceiveItem->purchaseItem->updateStatus();

        //Change the purchase status
        $fabricReceiveItem->purchaseOrder->updateStatus();
    }

    /**
     * Handle the receive item fabric "force deleted" event.
     *
     * @param  \App\Models\FabricReceiveItem  $fabricReceiveItem
     * @return void
     */
    public function forceDeleted(FabricReceiveItem $fabricReceiveItem)
    {
        // Update the purchase total Receive Amount
        $fabricReceiveItem->purchaseOrder->updateReceiveAmount();

        //Update the purchase item receive quantity
        $fabricReceiveItem->purchaseItem->updateReceiveQuantity();

        //Update the purchase item receive amount
        $fabricReceiveItem->purchaseItem->updateReceiveAmount();

        //Change the purchase item status
        $fabricReceiveItem->purchaseItem->updateStatus();

        //Change the purchase status
        $fabricReceiveItem->purchaseOrder->updateStatus();
    }
}
