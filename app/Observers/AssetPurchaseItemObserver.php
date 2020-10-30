<?php

namespace App\Observers;

use Exception;
use App\Models\Asset;
use App\Facades\Settings;
use App\Models\AssetPurchaseItem;

class AssetPurchaseItemObserver
{

    /**
     * Handle the purchase item asset "saving" event.
     *
     * @param  \App\Models\AssetPurchaseItem  $assetPurchaseItem
     * @return void
     */
    public function saving(AssetPurchaseItem $assetPurchaseItem)
    {
        if($assetPurchaseItem->purchaseOrder->purchaseItems()->count() > Settings::maxInvoiceItem()){
            throw new Exception('Maximum item exceeded.');
        }

        //Get the asset
        $asset = Asset::find($assetPurchaseItem->assetId);

        //Set the purchase amount
        $assetPurchaseItem->purchaseRate = $asset->rate;
        if(empty($assetPurchaseItem->unitId)){
            $assetPurchaseItem->unitId = $asset->unitId;
        }
        $assetPurchaseItem->purchaseAmount = $asset->rate * $assetPurchaseItem->purchaseQuantity;
    }

    /**
     * Handle the purchase item asset "saved" event.
     *
     * @param  \App\Models\AssetPurchaseItem  $assetPurchaseItem
     * @return void
     */
    public function saved(AssetPurchaseItem $assetPurchaseItem)
    {
        //Update the total purchase amount
        $assetPurchaseItem->purchaseOrder->updatePurchaseAmount();
    }

    /**
     * Handle the purchase item asset "deleting" event.
     *
     * @param  \App\Models\AssetPurchaseItem  $assetPurchaseItem
     * @return void
     */
    public function deleting(AssetPurchaseItem $assetPurchaseItem)
    {
        //Update the total purchase amount
        if($assetPurchaseItem->receiveItems()->exists()){
            throw new Exception("You can not delete it now, there are some receive items related to it.");
        }
    }

    /**
     * Handle the purchase item asset "deleted" event.
     *
     * @param  \App\Models\AssetPurchaseItem  $assetPurchaseItem
     * @return void
     */
    public function deleted(AssetPurchaseItem $assetPurchaseItem)
    {
        //Update the total purchase amount
        $assetPurchaseItem->purchaseOrder->updatePurchaseAmount();
    }

    /**
     * Handle the purchase item asset "restored" event.
     *
     * @param  \App\Models\AssetPurchaseItem  $assetPurchaseItem
     * @return void
     */
    public function restored(AssetPurchaseItem $assetPurchaseItem)
    {
        //Update the total purchase amount
        $assetPurchaseItem->purchaseOrder->updatePurchaseAmount();
    }

    /**
     * Handle the purchase item asset "force deleted" event.
     *
     * @param  \App\Models\AssetPurchaseItem  $assetPurchaseItem
     * @return void
     */
    public function forceDeleted(AssetPurchaseItem $assetPurchaseItem)
    {
        //Update the total purchase amount
        $assetPurchaseItem->purchaseOrder->updatePurchaseAmount();
    }
}
