<?php

namespace App\Observers;

use App\Models\Fabric;
use App\Models\FabricTransferItem;
use App\Models\FabricTransferReceiveItem;

class FabricTransferReceiveItemObserver
{
    /**
     * Handle the fabric transfer receive item "saving" event.
     *
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return void
     */
    public function saving(FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        //Get the related transfer item
        $transferItem = FabricTransferItem::find($fabricTransferReceiveItem->transferItemId);

        //Set invoice id
        $fabricTransferReceiveItem->invoiceId = $transferItem->invoiceId;

        //Create or find the fabric
        $fabric  = Fabric::firstOrCreate(
            [
                'code'        => $transferItem->fabric->code,
                'location_id' => $transferItem->invoice->receiverId,
            ],

            [
                'name'             => $transferItem->fabric->name,
                'description'      => $transferItem->fabric->description,
                'rate'             => $transferItem->fabric->rate,
                'opening_quantity' => 0,
                'quantity'         => 0,
                'alert_quantity'   => 0,
                'unit_id'          => $transferItem->fabric->unitId,
            ]
        );

        //Set fabric id
        $fabricTransferReceiveItem->fabricId = $fabric->id;
        //Set rate
        $fabricTransferReceiveItem->rate = $fabric->rate;

        if(empty($fabricTransferReceiveItem->unitId)){

            $fabricTransferReceiveItem->unitId = $fabric->unitId;
        }
        //Set Amount
        $fabricTransferReceiveItem->amount = $fabric->rate * $fabricTransferReceiveItem->quantity;
    }

    /**
     * Handle the fabric transfer receive item "saved" event.
     *
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return void
     */
    public function saved(FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        //Update the transfer item receive quantity
        $fabricTransferReceiveItem->transferItem->updateReceiveQuantity();

        //Update the transfer item receive amount
        $fabricTransferReceiveItem->transferItem->updateReceiveAmount();

        //Change the transfer item status
        $fabricTransferReceiveItem->transferItem->updateStatus();

        // Update the invoice total Receive Amount
        $fabricTransferReceiveItem->invoice->updateReceiveAmount();

        //Change the invoice status
        $fabricTransferReceiveItem->invoice->updateStatus();
    }

    /**
     * Handle the fabric transfer receive item "deleted" event.
     *
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return void
     */
    public function deleted(FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        //Update the transfer item receive quantity
        $fabricTransferReceiveItem->transferItem->updateReceiveQuantity();

        //Update the transfer item receive amount
        $fabricTransferReceiveItem->transferItem->updateReceiveAmount();

        //Change the transfer item status
        $fabricTransferReceiveItem->transferItem->updateStatus();

        // Update the invoice total Receive Amount
        $fabricTransferReceiveItem->invoice->updateReceiveAmount();

        //Change the invoice status
        $fabricTransferReceiveItem->invoice->updateStatus();
    }

    /**
     * Handle the fabric transfer receive item "restored" event.
     *
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return void
     */
    public function restored(FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        //Update the transfer item receive quantity
        $fabricTransferReceiveItem->transferItem->updateReceiveQuantity();

        //Update the transfer item receive amount
        $fabricTransferReceiveItem->transferItem->updateReceiveAmount();

        //Change the transfer item status
        $fabricTransferReceiveItem->transferItem->updateStatus();

        // Update the invoice total Receive Amount
        $fabricTransferReceiveItem->invoice->updateReceiveAmount();

        //Change the invoice status
        $fabricTransferReceiveItem->invoice->updateStatus();
    }

    /**
     * Handle the fabric transfer receive item "force deleted" event.
     *
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return void
     */
    public function forceDeleted(FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        //Update the transfer item receive quantity
        $fabricTransferReceiveItem->transferItem->updateReceiveQuantity();

        //Update the transfer item receive amount
        $fabricTransferReceiveItem->transferItem->updateReceiveAmount();

        //Change the transfer item status
        $fabricTransferReceiveItem->transferItem->updateStatus();

        // Update the invoice total Receive Amount
        $fabricTransferReceiveItem->invoice->updateReceiveAmount();

        //Change the invoice status
        $fabricTransferReceiveItem->invoice->updateStatus();
    }
}
