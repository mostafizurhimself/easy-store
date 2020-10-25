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
     * Handle the fabric transfer invoice "updated" event.
     *
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return void
     */
    public function updated(FabricTransferInvoice $fabricTransferInvoice)
    {
        //
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
