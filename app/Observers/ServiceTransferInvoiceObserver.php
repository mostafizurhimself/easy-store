<?php

namespace App\Observers;

use App\Models\ServiceTransferInvoice;

class ServiceTransferInvoiceObserver
{
    /**
     * Handle the service transfer invoice "created" event.
     *
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return void
     */
    public function created(ServiceTransferInvoice $serviceTransferInvoice)
    {
        //
    }

    /**
     * Handle the service transfer invoice "updated" event.
     *
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return void
     */
    public function updated(ServiceTransferInvoice $serviceTransferInvoice)
    {
        if($serviceTransferInvoice->isDirty('location_id') || $serviceTransferInvoice->isDirty('receiver_id')){
            $serviceTransferInvoice->transferItems()->forceDelete();
        }
    }

    /**
     * Handle the service transfer invoice "deleted" event.
     *
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return void
     */
    public function deleted(ServiceTransferInvoice $serviceTransferInvoice)
    {
        //
    }

    /**
     * Handle the service transfer invoice "restored" event.
     *
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return void
     */
    public function restored(ServiceTransferInvoice $serviceTransferInvoice)
    {
        //
    }

    /**
     * Handle the service transfer invoice "force deleted" event.
     *
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return void
     */
    public function forceDeleted(ServiceTransferInvoice $serviceTransferInvoice)
    {
        //
    }
}
