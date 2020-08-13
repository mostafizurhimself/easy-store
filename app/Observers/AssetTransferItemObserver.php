<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\AssetTransferItem;

class AssetTransferItemObserver
{
    /**
     * Handle the asset transfer item "saving" event.
     *
     * @param  \App\Models\AssetTransferItem  $assetTransferItem
     * @return void
     */
    public function saving(AssetTransferItem $assetTransferItem)
    {
        //Get the asset
        $asset = Asset::find($assetTransferItem->assetId);

        //Set the transfer amount
        $assetTransferItem->rate = $asset->rate;
        $assetTransferItem->amount = $asset->rate * $assetTransferItem->quantity;
    }

    /**
     * Handle the asset transfer item "saved" event.
     *
     * @param  \App\Models\AssetTransferItem  $assetTransferItem
     * @return void
     */
    public function saved(AssetTransferItem $assetTransferItem)
    {
        //Update the total amount
        $assetTransferItem->transferOrder->updateTotalAmount();
    }


    /**
     * Handle the asset transfer item "deleted" event.
     *
     * @param  \App\Models\AssetTransferItem  $assetTransferItem
     * @return void
     */
    public function deleted(AssetTransferItem $assetTransferItem)
    {
        //Update the total amount
        $assetTransferItem->transferOrder->updateTotalAmount();
    }

    /**
     * Handle the asset transfer item "restored" event.
     *
     * @param  \App\Models\AssetTransferItem  $assetTransferItem
     * @return void
     */
    public function restored(AssetTransferItem $assetTransferItem)
    {
        //Update the total amount
        $assetTransferItem->transferOrder->updateTotalAmount();
    }

    /**
     * Handle the asset transfer item "force deleted" event.
     *
     * @param  \App\Models\AssetTransferItem  $assetTransferItem
     * @return void
     */
    public function forceDeleted(AssetTransferItem $assetTransferItem)
    {
        //Update the total amount
        $assetTransferItem->transferOrder->updateTotalAmount();
    }
}
