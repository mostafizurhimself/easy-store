<?php

namespace App\Observers;

use App\Models\AssetReceiveItem;
use App\Models\AssetPurchaseItem;

class AssetReceiveItemObserver
{

    /**
     * Handle the receive item asset "saving" event.
     *
     * @param  \App\Models\AssetReceiveItem  $assetReceiveItem
     * @return void
     */
    public function saving(AssetReceiveItem $assetReceiveItem)
    {
        //Get the related purchase
        $purchaseItem = AssetPurchaseItem::find($assetReceiveItem->purchaseItemId);

        //Set purchase id
        $assetReceiveItem->purchaseOrderId = $purchaseItem->purchaseOrderId;
        //Set asset id
        $assetReceiveItem->assetId = $purchaseItem->assetId;
        //Set rate
        if(empty($assetReceiveItem->rate)){
            $assetReceiveItem->rate = $purchaseItem->purchaseRate;
        }
        if(empty($assetReceiveItem->unitId)){
            $assetReceiveItem->unitId = $purchaseItem->asset->unitId;
        }
        //Set Amount
        $assetReceiveItem->amount = $purchaseItem->purchaseRate * $assetReceiveItem->quantity;
    }

    /**
     * Handle the receive item asset "saved" event.
     *
     * @param  \App\Models\AssetReceiveItem  $assetReceiveItem
     * @return void
     */
    public function saved(AssetReceiveItem $assetReceiveItem)
    {
        // Update the purchase total Receive Amount
        $assetReceiveItem->purchaseOrder->updateReceiveAmount();

        //Update the purchase item receive quantity
        $assetReceiveItem->purchaseItem->updateReceiveQuantity();

        //Update the purchase item receive amount
        $assetReceiveItem->purchaseItem->updateReceiveAmount();

        //Change the purchase item status
        $assetReceiveItem->purchaseItem->updateStatus();

        //Change the purchase status
        $assetReceiveItem->purchaseOrder->updateStatus();
    }

    /**
     * Handle the receive item asset "deleted" event.
     *
     * @param  \App\Models\AssetReceiveItem  $assetReceiveItem
     * @return void
     */
    public function deleted(AssetReceiveItem $assetReceiveItem)
    {
        // Update the purchase total Receive Amount
        $assetReceiveItem->purchaseOrder->updateReceiveAmount();

        //Update the purchase item receive quantity
        $assetReceiveItem->purchaseItem->updateReceiveQuantity();

        //Update the purchase item receive amount
        $assetReceiveItem->purchaseItem->updateReceiveAmount();

        //Change the purchase item status
        $assetReceiveItem->purchaseItem->updateStatus();

        //Change the purchase status
        $assetReceiveItem->purchaseOrder->updateStatus();
    }

    /**
     * Handle the receive item asset "restored" event.
     *
     * @param  \App\Models\AssetReceiveItem  $assetReceiveItem
     * @return void
     */
    public function restored(AssetReceiveItem $assetReceiveItem)
    {
        // Update the purchase total Receive Amount
        $assetReceiveItem->purchaseOrder->updateReceiveAmount();

        //Update the purchase item receive quantity
        $assetReceiveItem->purchaseItem->updateReceiveQuantity();

        //Update the purchase item receive amount
        $assetReceiveItem->purchaseItem->updateReceiveAmount();

        //Change the purchase item status
        $assetReceiveItem->purchaseItem->updateStatus();

        //Change the purchase status
        $assetReceiveItem->purchaseOrder->updateStatus();
    }

    /**
     * Handle the receive item asset "force deleted" event.
     *
     * @param  \App\Models\AssetReceiveItem  $assetReceiveItem
     * @return void
     */
    public function forceDeleted(AssetReceiveItem $assetReceiveItem)
    {
        // Update the purchase total Receive Amount
        $assetReceiveItem->purchaseOrder->updateReceiveAmount();

        //Update the purchase item receive quantity
        $assetReceiveItem->purchaseItem->updateReceiveQuantity();

        //Update the purchase item receive amount
        $assetReceiveItem->purchaseItem->updateReceiveAmount();

        //Change the purchase item status
        $assetReceiveItem->purchaseItem->updateStatus();

        //Change the purchase status
        $assetReceiveItem->purchaseOrder->updateStatus();
    }
}
