<?php

namespace App\Observers;

use App\Models\Service;
use App\Models\ServiceTransferItem;

class ServiceTransferItemObserver
{
    /**
     * Handle the service transfer item "saving" event.
     *
     * @param  \App\Models\ServiceTransferItem  $serviceTransferItem
     * @return void
     */
    public function saving(ServiceTransferItem $serviceTransferItem)
    {
        //Get the service
        $service = Service::find($serviceTransferItem->serviceId);

        //Set the dispatch amount
        $serviceTransferItem->rate = $service->rate;

        if(empty($serviceTransferItem->unitId)){
            $serviceTransferItem->unitId = $service->unitId;
        }
        $serviceTransferItem->transferAmount = $service->rate * $serviceTransferItem->transferQuantity;
    }

    /**
     * Handle the service transfer item "saved" event.
     *
     * @param  \App\Models\ServiceTransferItem  $serviceTransferItem
     * @return void
     */
    public function saved(ServiceTransferItem $serviceTransferItem)
    {
        //Update the total amount of invoice
        $serviceTransferItem->invoice->updateTransferAmount();
    }

    /**
     * Handle the service transfer item "deleted" event.
     *
     * @param  \App\Models\ServiceTransferItem  $serviceTransferItem
     * @return void
     */
    public function deleted(ServiceTransferItem $serviceTransferItem)
    {
        //Update the total amount of invoice
        $serviceTransferItem->invoice->updateTransferAmount();
    }

    /**
     * Handle the service transfer item "restored" event.
     *
     * @param  \App\Models\ServiceTransferItem  $serviceTransferItem
     * @return void
     */
    public function restored(ServiceTransferItem $serviceTransferItem)
    {
        //Update the total amount of invoice
        $serviceTransferItem->invoice->updateTransferAmount();
    }

    /**
     * Handle the service transfer item "force deleted" event.
     *
     * @param  \App\Models\ServiceTransferItem  $serviceTransferItem
     * @return void
     */
    public function forceDeleted(ServiceTransferItem $serviceTransferItem)
    {
        //Update the total amount of invoice
        $serviceTransferItem->invoice->updateTransferAmount();
    }
}
