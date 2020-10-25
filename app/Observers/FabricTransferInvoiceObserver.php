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
     * Handle the fabric transfer invoice "deleted" event.
     *
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return void
     */
    public function deleted(FabricTransferInvoice $fabricTransferInvoice)
    {
        //
    }

    /**
     * Handle the fabric transfer invoice "restored" event.
     *
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return void
     */
    public function restored(FabricTransferInvoice $fabricTransferInvoice)
    {
        //
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
