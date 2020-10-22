<?php

namespace App\Observers;

use App\Models\Material;
use App\Models\MaterialTransferItem;
use App\Models\MaterialTransferReceiveItem;

class MaterialTransferReceiveItemObserver
{
    /**
     * Handle the material transfer receive item "saving" event.
     *
     * @param  \App\Models\MaterialTransferReceiveItem  $materialTransferReceiveItem
     * @return void
     */
    public function saving(MaterialTransferReceiveItem $materialTransferReceiveItem)
    {
        //Get the related transfer item
        $transferItem = MaterialTransferItem::find($materialTransferReceiveItem->transferItemId);

        //Set invoice id
        $materialTransferReceiveItem->invoiceId = $transferItem->invoiceId;

        //Create or find the material
        $material  = Material::firstOrCreate(
            [
                'code'        => $transferItem->material->code,
                'location_id' => $transferItem->invoice->receiverId,
            ],

            [
                'name'             => $transferItem->material->name,
                'description'      => $transferItem->material->description,
                'rate'             => $transferItem->material->rate,
                'opening_quantity' => 0,
                'quantity'         => 0,
                'alert_quantity'   => 0,
                'unit_id'          => $transferItem->material->unitId,
            ]
        );

        //Set material id
        $materialTransferReceiveItem->materialId = $material->id;
        //Set rate
        $materialTransferReceiveItem->rate = $material->rate;

        if(empty($materialTransferReceiveItem->unitId)){

            $materialTransferReceiveItem->unitId = $material->unitId;
        }
        //Set Amount
        $materialTransferReceiveItem->amount = $material->rate * $materialTransferReceiveItem->quantity;
    }

    /**
     * Handle the material transfer receive item "saved" event.
     *
     * @param  \App\Models\MaterialTransferReceiveItem  $materialTransferReceiveItem
     * @return void
     */
    public function saved(MaterialTransferReceiveItem $materialTransferReceiveItem)
    {
        //Update the transfer item receive quantity
        $materialTransferReceiveItem->transferItem->updateReceiveQuantity();

        //Update the transfer item receive amount
        $materialTransferReceiveItem->transferItem->updateReceiveAmount();

        //Change the transfer item status
        $materialTransferReceiveItem->transferItem->updateStatus();

        // Update the invoice total Receive Amount
        $materialTransferReceiveItem->invoice->updateReceiveAmount();

        //Change the invoice status
        $materialTransferReceiveItem->invoice->updateStatus();
    }

    /**
     * Handle the material transfer receive item "deleted" event.
     *
     * @param  \App\Models\MaterialTransferReceiveItem  $materialTransferReceiveItem
     * @return void
     */
    public function deleted(MaterialTransferReceiveItem $materialTransferReceiveItem)
    {
        //Update the transfer item receive quantity
        $materialTransferReceiveItem->transferItem->updateReceiveQuantity();

        //Update the transfer item receive amount
        $materialTransferReceiveItem->transferItem->updateReceiveAmount();

        //Change the transfer item status
        $materialTransferReceiveItem->transferItem->updateStatus();

        // Update the invoice total Receive Amount
        $materialTransferReceiveItem->invoice->updateReceiveAmount();

        //Change the invoice status
        $materialTransferReceiveItem->invoice->updateStatus();
    }

    /**
     * Handle the material transfer receive item "restored" event.
     *
     * @param  \App\Models\MaterialTransferReceiveItem  $materialTransferReceiveItem
     * @return void
     */
    public function restored(MaterialTransferReceiveItem $materialTransferReceiveItem)
    {
        //Update the transfer item receive quantity
        $materialTransferReceiveItem->transferItem->updateReceiveQuantity();

        //Update the transfer item receive amount
        $materialTransferReceiveItem->transferItem->updateReceiveAmount();

        //Change the transfer item status
        $materialTransferReceiveItem->transferItem->updateStatus();

        // Update the invoice total Receive Amount
        $materialTransferReceiveItem->invoice->updateReceiveAmount();

        //Change the invoice status
        $materialTransferReceiveItem->invoice->updateStatus();
    }

    /**
     * Handle the material transfer receive item "force deleted" event.
     *
     * @param  \App\Models\MaterialTransferReceiveItem  $materialTransferReceiveItem
     * @return void
     */
    public function forceDeleted(MaterialTransferReceiveItem $materialTransferReceiveItem)
    {
        //Update the transfer item receive quantity
        $materialTransferReceiveItem->transferItem->updateReceiveQuantity();

        //Update the transfer item receive amount
        $materialTransferReceiveItem->transferItem->updateReceiveAmount();

        //Change the transfer item status
        $materialTransferReceiveItem->transferItem->updateStatus();

        // Update the invoice total Receive Amount
        $materialTransferReceiveItem->invoice->updateReceiveAmount();

        //Change the invoice status
        $materialTransferReceiveItem->invoice->updateStatus();
    }
}
