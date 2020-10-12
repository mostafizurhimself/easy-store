<?php

namespace App\Observers;

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
        //Get the related dispatch
        $dispatch = ServiceDispatch::find($serviceTransferReceiveItem->dispatchId);

        //Set invoice id
        $serviceTransferReceiveItem->invoiceId = $dispatch->invoiceId;
        //Set service id
        $serviceTransferReceiveItem->serviceId = $dispatch->serviceId;
        //Set rate
        if (empty($serviceTransferReceiveItem->rate)) {
            $serviceTransferReceiveItem->rate = $dispatch->rate;
        }

        if(empty($serviceTransferReceiveItem->unitId)){
            $serviceTransferReceiveItem->unitId = $dispatch->service->unitId;
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
        //
    }

    /**
     * Handle the service transfer receive item "deleted" event.
     *
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return void
     */
    public function deleted(ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
        //
    }

    /**
     * Handle the service transfer receive item "restored" event.
     *
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return void
     */
    public function restored(ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
        //
    }

    /**
     * Handle the service transfer receive item "force deleted" event.
     *
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return void
     */
    public function forceDeleted(ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
        //
    }
}
