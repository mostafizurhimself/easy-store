<?php

namespace App\Observers;

use App\Models\ServiceReceive;
use App\Models\ServiceDispatch;

class ServiceReceiveObserver
{
    /**
     * Handle the service receive "saving" event.
     *
     * @param  \App\Models\ServiceReceive  $serviceReceive
     * @return void
     */
    public function saving(ServiceReceive $serviceReceive)
    {
        //Get the related dispatch
        $dispatch = ServiceDispatch::find($serviceReceive->dispatchId);

        //Set invoice id
        $serviceReceive->invoiceId = $dispatch->invoiceId;
        //Set service id
        $serviceReceive->serviceId = $dispatch->serviceId;
        //Set rate
        if (empty($serviceReceive->rate)) {
            $serviceReceive->rate = $dispatch->rate;
        }

        if(empty($serviceReceive->unitId)){
            $serviceReceive->unitId = $dispatch->service->unitId;
        }
        //Set Amount
        $serviceReceive->amount = $serviceReceive->rate * $serviceReceive->quantity;
    }

    /**
     * Handle the service receive "saved" event.
     *
     * @param  \App\Models\ServiceReceive  $serviceReceive
     * @return void
     */
    public function saved(ServiceReceive $serviceReceive)
    {
        // Update the invoice  Receive Amount
        $serviceReceive->invoice->updateReceiveAmount();

        //Update the dispatch receive quantity
        $serviceReceive->dispatch->updateReceiveQuantity();

        //Update the dispatch receive amount
        $serviceReceive->dispatch->updateReceiveAmount();

        //Change the dispatch status
        $serviceReceive->dispatch->updateStatus();

        //Change the purchase status
        $serviceReceive->invoice->updateStatus();
    }

    /**
     * Handle the service receive "deleted" event.
     *
     * @param  \App\Models\ServiceReceive  $serviceReceive
     * @return void
     */
    public function deleted(ServiceReceive $serviceReceive)
    {
        // Update the invoice  Receive Amount
        $serviceReceive->invoice->updateReceiveAmount();

        //Update the dispatch receive quantity
        $serviceReceive->dispatch->updateReceiveQuantity();

        //Update the dispatch receive amount
        $serviceReceive->dispatch->updateReceiveAmount();

        //Change the dispatch status
        $serviceReceive->dispatch->updateStatus();

        //Change the purchase status
        $serviceReceive->invoice->updateStatus();
    }

    /**
     * Handle the service receive "restored" event.
     *
     * @param  \App\Models\ServiceReceive  $serviceReceive
     * @return void
     */
    public function restored(ServiceReceive $serviceReceive)
    {
        // Update the invoice  Receive Amount
        $serviceReceive->invoice->updateReceiveAmount();

        //Update the dispatch receive quantity
        $serviceReceive->dispatch->updateReceiveQuantity();

        //Update the dispatch receive amount
        $serviceReceive->dispatch->updateReceiveAmount();

        //Change the dispatch status
        $serviceReceive->dispatch->updateStatus();

        //Change the purchase status
        $serviceReceive->invoice->updateStatus();
    }

    /**
     * Handle the service receive "force deleted" event.
     *
     * @param  \App\Models\ServiceReceive  $serviceReceive
     * @return void
     */
    public function forceDeleted(ServiceReceive $serviceReceive)
    {
        // Update the invoice  Receive Amount
        $serviceReceive->invoice->updateReceiveAmount();

        //Update the dispatch receive quantity
        $serviceReceive->dispatch->updateReceiveQuantity();

        //Update the dispatch receive amount
        $serviceReceive->dispatch->updateReceiveAmount();

        //Change the dispatch status
        $serviceReceive->dispatch->updateStatus();

        //Change the purchase status
        $serviceReceive->invoice->updateStatus();
    }
}
