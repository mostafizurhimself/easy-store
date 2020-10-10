<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\AssetRequisitionItem;

class AssetRequisitionItemObserver
{
    /**
     * Handle the asset requisition item "saving" event.
     *
     * @param  \App\Models\AssetRequisitionItem  $assetRequisitionItem
     * @return void
     */
    public function saving(AssetRequisitionItem $assetRequisitionItem)
    {
        //Get the asset
        $asset = Asset::find($assetRequisitionItem->assetId);

        //Set the purchase amount
        $assetRequisitionItem->requisitionRate = $asset->rate;
        if(empty($assetRequisitionItem->unitId)){
            $assetRequisitionItem->unitId = $asset->unitId;
        }
        $assetRequisitionItem->requisitionAmount = $asset->rate * $assetRequisitionItem->requisitionQuantity;
    }

    /**
     * Handle the asset requisition item "saved" event.
     *
     * @param  \App\Models\AssetRequisitionItem  $assetRequisitionItem
     * @return void
     */
    public function saved(AssetRequisitionItem $assetRequisitionItem)
    {
        //Update the total requisition amount
        $assetRequisitionItem->requisition->updateRequisitionAmount();
    }

    /**
     * Handle the asset requisition item "deleted" event.
     *
     * @param  \App\Models\AssetRequisitionItem  $assetRequisitionItem
     * @return void
     */
    public function deleted(AssetRequisitionItem $assetRequisitionItem)
    {
        //Update the total requisition amount
        $assetRequisitionItem->requisition->updateRequisitionAmount();
    }

    /**
     * Handle the asset requisition item "restored" event.
     *
     * @param  \App\Models\AssetRequisitionItem  $assetRequisitionItem
     * @return void
     */
    public function restored(AssetRequisitionItem $assetRequisitionItem)
    {
        //Update the total requisition amount
        $assetRequisitionItem->requisition->updateRequisitionAmount();
    }

    /**
     * Handle the asset requisition item "force deleted" event.
     *
     * @param  \App\Models\AssetRequisitionItem  $assetRequisitionItem
     * @return void
     */
    public function forceDeleted(AssetRequisitionItem $assetRequisitionItem)
    {
        //Update the total requisition amount
        $assetRequisitionItem->requisition->updateRequisitionAmount();
    }
}
