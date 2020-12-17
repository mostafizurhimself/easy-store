<?php

namespace App\Observers;

use App\Models\FabricTransferInvoice;

class FabricTransferInvoiceObserver
{
    /**
     * Handle the fabric transfer invoice "created" event.
     *
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return void
     */
    public function created(FabricTransferInvoice $fabricTransferInvoice)
    {
        //
    }

    /**
     * Handle the fabric transfer invoice "updating" event.
     *
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return void
     */
    public function updating(FabricTransferInvoice $fabricTransferInvoice)
    {
        if($fabricTransferInvoice->isDirty('location_id')){
            $fabricTransferInvoice->transferItems()->forceDelete();
        }
    }

    /**
     * Handle the fabric transfer invoice "deleting" event.
     *
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return void
     */
    public function deleting(FabricTransferInvoice $fabricTransferInvoice)
    {
        if($fabricTransferInvoice->isForceDeleting()){
            // Force Delete transfer items
            $fabricTransferInvoice->transferItems()->forceDelete();

            // Force Delete receive items
            $fabricTransferInvoice->receiveItems()->forceDelete();
        }else{
            // Delete transfer items
            $fabricTransferInvoice->transferItems()->delete();

            // Delete receive items
            $fabricTransferInvoice->receiveItems()->delete();
        }
    }

    /**
     * Handle the fabric transfer invoice "restored" event.
     *
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return void
     */
    public function restored(FabricTransferInvoice $fabricTransferInvoice)
    {
        // Restore transfer items
        $fabricTransferInvoice->transferItems()->restore();
        // Restore receive items
        $fabricTransferInvoice->receiveItems()->restore();
    }

    /**
     * Handle the fabric transfer invoice "force deleted" event.
     *
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return void
     */
    public function forceDeleted(FabricTransferInvoice $fabricTransferInvoice)
    {
        //
    }
}
