<?php

namespace App\Observers;

use Exception;
use App\Models\Asset;
use App\Facades\Settings;
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
        if($assetRequisitionItem->requisition->requisitionItems()->count() > Settings::maxInvoiceItem()){
            throw new Exception('Maximum item exceeded.');
        }

        //Get the asset
        $asset = Asset::find($assetRequisitionItem->assetId);

        //Set the purchase amount
        $assetRequisitionItem->requisitionRate = $asset->rate;
        if(empty($assetRequisitionItem->unitId)){
            $assetRequisitionItem->unitId = $asset->unitId;
        }
        $assetRequisitionItem->requisitionAmount = $assetRequisitionItem->requisitionRate * $assetRequisitionItem->requisitionQuantity;
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
