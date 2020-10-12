<?php

namespace App\Observers;

use App\Models\ServiceTransferItem;
use App\Models\ServiceTransferReceiveItem;

class ServiceTransferReceiveItemObserver
{
    /**
     * Handle the service transfer receive item "created" event.
     *
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return void
     */
    public function created(ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
        //Get the related transfer item
        $transfer = ServiceTransferItem::find($serviceTransferReceiveItem->transferId);

        //Set invoice id
        $serviceTransferReceiveItem->invoiceId = $transfer->invoiceId;
        //Set service id
        $serviceTransferReceiveItem->serviceId = $transfer->serviceId;
        //Set rate
        if (empty($serviceTransferReceiveItem->rate)) {
            $serviceTransferReceiveItem->rate = $transfer->rate;
        }

        if(empty($serviceTransferReceiveItem->unitId)){
            $serviceTransferReceiveItem->unitId = $transfer->service->unitId;
        }
        //Set Amount
        $serviceTransferReceiveItem->amount = $serviceTransferReceiveItem->rate * $serviceTransferReceiveItem->quantity;
    }

    /**
     * Handle the service transfer receive item "updated" event.
     *
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return void
     */
    public function updated(ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
        // Update the invoice  Receive Amount
        $serviceTransferReceiveItem->invoice->updateReceiveAmount();

        //Update the transfer receive quantity
        $serviceTransferReceiveItem->transfer->updateReceiveQuantity();

        //Update the transfer receive amount
        $serviceTransferReceiveItem->transfer->updateReceiveAmount();

        //Change the transfer status
        $serviceTransferReceiveItem->transfer->updateStatus();

        //Change the purchase status
        $serviceTransferReceiveItem->invoice->updateStatus();
    }

    /**
     * Handle the service transfer receive item "deleted" event.
     *
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return void
     */
    public function deleted(ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
         // Update the invoice  Receive Amount
         $serviceTransferReceiveItem->invoice->updateReceiveAmount();

         //Update the transfer receive quantity
         $serviceTransferReceiveItem->transfer->updateReceiveQuantity();

         //Update the transfer receive amount
         $serviceTransferReceiveItem->transfer->updateReceiveAmount();

         //Change the transfer status
         $serviceTransferReceiveItem->transfer->updateStatus();

         //Change the purchase status
         $serviceTransferReceiveItem->invoice->updateStatus();
    }

    /**
     * Handle the service transfer receive item "restored" event.
     *
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return void
     */
    public function restored(ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
         // Update the invoice  Receive Amount
         $serviceTransferReceiveItem->invoice->updateReceiveAmount();

         //Update the transfer receive quantity
         $serviceTransferReceiveItem->transfer->updateReceiveQuantity();

         //Update the transfer receive amount
         $serviceTransferReceiveItem->transfer->updateReceiveAmount();

         //Change the transfer status
         $serviceTransferReceiveItem->transfer->updateStatus();

         //Change the purchase status
         $serviceTransferReceiveItem->invoice->updateStatus();
    }

    /**
     * Handle the service transfer receive item "force deleted" event.
     *
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return void
     */
    public function forceDeleted(ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
         // Update the invoice  Receive Amount
         $serviceTransferReceiveItem->invoice->updateReceiveAmount();

         //Update the transfer receive quantity
         $serviceTransferReceiveItem->transfer->updateReceiveQuantity();

         //Update the transfer receive amount
         $serviceTransferReceiveItem->transfer->updateReceiveAmount();

         //Change the transfer status
         $serviceTransferReceiveItem->transfer->updateStatus();

         //Change the purchase status
         $serviceTransferReceiveItem->invoice->updateStatus();
    }
}
