<?php

namespace App\Observers;

use App\Models\Service;
use App\Models\ServiceTransferItem;
use App\Models\ServiceTransferReceiveItem;

class ServiceTransferReceiveItemObserver
{
    /**
     * Handle the service transfer receive item "saving" event.
     *
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return void
     */
    public function saving(ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
        //Get the related transfer item
        $transfer = ServiceTransferItem::find($serviceTransferReceiveItem->transferId);

        //Set invoice id
        $serviceTransferReceiveItem->invoiceId = $transfer->invoiceId;

        //Create or find the service
        $service  = Service::firstOrCreate(
            [
                'code'        => $transfer->service->code,
                'location_id' => $transfer->invoice->receiverId,
            ],

            [
                'name'             => $transfer->service->name,
                'description'      => $transfer->service->description,
                'rate'             => $transfer->service->rate,
                'total_dispatch_quantity' => 0,
                'total_receive_quantity'  => 0,
                'unit_id'          => $transfer->service->unitId,
            ]
        );

        //Set asset id
        $serviceTransferReceiveItem->serviceId = $service->id;

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
     * Handle the service transfer receive item "saved" event.
     *
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return void
     */
    public function saved(ServiceTransferReceiveItem $serviceTransferReceiveItem)
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
