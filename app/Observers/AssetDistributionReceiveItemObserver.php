<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\AssetDistributionReceiveItem;

class AssetDistributionReceiveItemObserver
{
    /**
     * Handle the asset distribution receive item "saving" event.
     *
     * @param  \App\Models\AssetDistributionReceiveItem  $assetDistributionReceiveItem
     * @return void
     */
    public function saving(AssetDistributionReceiveItem $assetDistributionReceiveItem)
    {
        //Get the related distribution item
        $distributionItem = AssetDistributionItem::find($assetDistributionReceiveItem->distributionItemId);

        //Set invoice id
        $assetDistributionReceiveItem->invoiceId = $distributionItem->invoiceId;

        //Create or find the asset
        $asset  = Asset::firstOrCreate(
            ['code' => $distributionItem->asset->code],

            [
                'location_id' => $distributionItem->invoice->receiverId,
                'name' => $distributionItem->asset->name,
                'description' => $distributionItem->asset->description,
                'rate' => $distributionItem->asset->rate,
                'unit_id' => $distributionItem->asset->unitId,
            ]
        );

        //Set asset id
        $assetDistributionReceiveItem->assetId = $asset->id;
        //Set rate
        $assetDistributionReceiveItem->rate = $asset->rate;
        //Set Amount
        $assetDistributionReceiveItem->amount = $asset->rate * $assetDistributionReceiveItem->quantity;
    }

    /**
     * Handle the asset distribution receive item "saved" event.
     *
     * @param  \App\Models\AssetDistributionReceiveItem  $assetDistributionReceiveItem
     * @return void
     */
    public function saved(AssetDistributionReceiveItem $assetDistributionReceiveItem)
    {
        //Update the distribution item receive quantity
        $assetDistributionReceiveItem->distributionItem->updateReceiveQuantity();

        //Update the distribution item receive amount
        $assetDistributionReceiveItem->distributionItem->updateReceiveAmount();

        //Change the distribution item status
        $assetDistributionReceiveItem->distributionItem->updateStatus();

        // Update the invoice total Receive Amount
        $assetDistributionReceiveItem->invoice->updateReceiveAmount();

        //Change the invoice status
        $assetDistributionReceiveItem->invoice->updateStatus();
    }

    /**
     * Handle the asset distribution receive item "deleted" event.
     *
     * @param  \App\Models\AssetDistributionReceiveItem  $assetDistributionReceiveItem
     * @return void
     */
    public function deleted(AssetDistributionReceiveItem $assetDistributionReceiveItem)
    {
        //Update the distribution item receive quantity
        $assetDistributionReceiveItem->distributionItem->updateReceiveQuantity();

        //Update the distribution item receive amount
        $assetDistributionReceiveItem->distributionItem->updateReceiveAmount();

        //Change the distribution item status
        $assetDistributionReceiveItem->distributionItem->updateStatus();

        // Update the invoice total Receive Amount
        $assetDistributionReceiveItem->invoice->updateReceiveAmount();

        //Change the invoice status
        $assetDistributionReceiveItem->invoice->updateStatus();
    }

    /**
     * Handle the asset distribution receive item "restored" event.
     *
     * @param  \App\Models\AssetDistributionReceiveItem  $assetDistributionReceiveItem
     * @return void
     */
    public function restored(AssetDistributionReceiveItem $assetDistributionReceiveItem)
    {
        //Update the distribution item receive quantity
        $assetDistributionReceiveItem->distributionItem->updateReceiveQuantity();

        //Update the distribution item receive amount
        $assetDistributionReceiveItem->distributionItem->updateReceiveAmount();

        //Change the distribution item status
        $assetDistributionReceiveItem->distributionItem->updateStatus();

        // Update the invoice total Receive Amount
        $assetDistributionReceiveItem->invoice->updateReceiveAmount();

        //Change the invoice status
        $assetDistributionReceiveItem->invoice->updateStatus();
    }

    /**
     * Handle the asset distribution receive item "force deleted" event.
     *
     * @param  \App\Models\AssetDistributionReceiveItem  $assetDistributionReceiveItem
     * @return void
     */
    public function forceDeleted(AssetDistributionReceiveItem $assetDistributionReceiveItem)
    {
        //Update the distribution item receive quantity
        $assetDistributionReceiveItem->distributionItem->updateReceiveQuantity();

        //Update the distribution item receive amount
        $assetDistributionReceiveItem->distributionItem->updateReceiveAmount();

        //Change the distribution item status
        $assetDistributionReceiveItem->distributionItem->updateStatus();

        // Update the invoice total Receive Amount
        $assetDistributionReceiveItem->invoice->updateReceiveAmount();

        //Change the invoice status
        $assetDistributionReceiveItem->invoice->updateStatus();
    }
}
