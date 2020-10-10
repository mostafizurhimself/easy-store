<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\AssetReturnItem;

class AssetReturnItemObserver
{
    /**
     * Handle the asset return item "saving" event.
     *
     * @param  \App\Models\AssetReturnItem  $assetReturnItem
     * @return void
     */
    public function saving(AssetReturnItem $assetReturnItem)
    {
        //Get the asset
        $asset = Asset::find($assetReturnItem->assetId);

        //Set the amount
        if (empty($assetReturnItem->rate)) {
            $assetReturnItem->rate = $asset->rate;
        }
        $assetReturnItem->unitId = $asset->unitId;
        $assetReturnItem->amount = $asset->rate * $assetReturnItem->quantity;
    }

    /**
     * Handle the asset return item "saved" event.
     *
     * @param  \App\Models\AssetReturnItem  $assetReturnItem
     * @return void
     */
    public function saved(AssetReturnItem $assetReturnItem)
    {
        //Update the total return amount
        $assetReturnItem->invoice->updateReturnAmount();
    }

    /**
     * Handle the asset return item "deleted" event.
     *
     * @param  \App\Models\AssetReturnItem  $assetReturnItem
     * @return void
     */
    public function deleted(AssetReturnItem $assetReturnItem)
    {
        //Update the total return amount
        $assetReturnItem->invoice->updateReturnAmount();
    }

    /**
     * Handle the asset return item "restored" event.
     *
     * @param  \App\Models\AssetReturnItem  $assetReturnItem
     * @return void
     */
    public function restored(AssetReturnItem $assetReturnItem)
    {
        //Update the total return amount
        $assetReturnItem->invoice->updateReturnAmount();
    }

    /**
     * Handle the asset return item "force deleted" event.
     *
     * @param  \App\Models\AssetReturnItem  $assetReturnItem
     * @return void
     */
    public function forceDeleted(AssetReturnItem $assetReturnItem)
    {
        //Update the total return amount
        $assetReturnItem->invoice->updateReturnAmount();
    }
}
