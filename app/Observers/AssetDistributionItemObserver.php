<?php

namespace App\Observers;

use Exception;
use App\Models\Asset;
use App\Facades\Settings;
use App\Models\AssetDistributionItem;

class AssetDistributionItemObserver
{
    /**
     * Handle the asset distribution item "saving" event.
     *
     * @param  \App\Models\AssetDistributionItem  $assetDistributionItem
     * @return void
     */
    public function saving(AssetDistributionItem $assetDistributionItem)
    {
        if($assetDistributionItem->invoice->distributionItems()->count() > Settings::maxInvoiceItem()){
            throw new Exception('Maximum item exceeded.');
        }

        //Set the requisiton item
        if ($assetDistributionItem->invoice->requisitionId) {
            $assetDistributionItem->requisitionId = $assetDistributionItem->invoice->requisitionId;
            $assetDistributionItem->requisitionItemId = $assetDistributionItem->invoice
                ->requisition
                ->requisitionItems
                ->where('asset_id', $assetDistributionItem->assetId)
                ->first()->id;
        }
        //Get the asset
        $asset = Asset::find($assetDistributionItem->assetId);

        //Set the distribution amount
        $assetDistributionItem->distributionRate = $asset->rate;
        if(empty($assetDistributionItem->unitId)){
            $assetDistributionItem->unitId = $asset->unitId;
        }
        $assetDistributionItem->distributionAmount = $assetDistributionItem->distributionRate * $assetDistributionItem->distributionQuantity;
    }

    /**
     * Handle the asset distribution item "saved" event.
     *
     * @param  \App\Models\AssetDistributionItem  $assetDistributionItem
     * @return void
     */
    public function saved(AssetDistributionItem $assetDistributionItem)
    {
        //Update the total distribution amount
        $assetDistributionItem->invoice->updateDistributionAmount();
    }

    /**
     * Handle the asset distribution item "deleted" event.
     *
     * @param  \App\Models\AssetDistributionItem  $assetDistributionItem
     * @return void
     */
    public function deleted(AssetDistributionItem $assetDistributionItem)
    {
        //Update the total distribution amount
        $assetDistributionItem->invoice->updateDistributionAmount();
    }

    /**
     * Handle the asset distribution item "restored" event.
     *
     * @param  \App\Models\AssetDistributionItem  $assetDistributionItem
     * @return void
     */
    public function restored(AssetDistributionItem $assetDistributionItem)
    {
        //Update the total distribution amount
        $assetDistributionItem->invoice->updateDistributionAmount();
    }

    /**
     * Handle the asset distribution item "force deleted" event.
     *
     * @param  \App\Models\AssetDistributionItem  $assetDistributionItem
     * @return void
     */
    public function forceDeleted(AssetDistributionItem $assetDistributionItem)
    {
        //Update the total distribution amount
        $assetDistributionItem->invoice->updateDistributionAmount();
    }
}
