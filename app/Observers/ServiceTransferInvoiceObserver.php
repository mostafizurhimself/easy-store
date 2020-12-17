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
     * Handle the service transfer invoice "deleting" event.
     *
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return void
     */
    public function deleting(ServiceTransferInvoice $serviceTransferInvoice)
    {
        if($serviceTransferInvoice->isForceDeleting()){
            // Force Delete related transfer items
            $serviceTransferInvoice->transferItems()->forceDelete();
            // Force Delete related receive items
            $serviceTransferInvoice->receiveItems()->forceDelete();
        }else{
            // Delete related transfer items
            $serviceTransferInvoice->transferItems()->delete();
            // Delete related receive items
            $serviceTransferInvoice->receiveItems()->delete();
        }
    }

    /**
     * Handle the service transfer invoice "restored" event.
     *
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return void
     */
    public function restored(ServiceTransferInvoice $serviceTransferInvoice)
    {
        // Restore related transfer items
        $serviceTransferInvoice->transferItems()->restore();
        // Restore related receive items
        $serviceTransferInvoice->receiveItems()->restore();
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
