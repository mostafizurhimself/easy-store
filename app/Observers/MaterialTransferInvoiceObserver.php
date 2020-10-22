<?php

namespace App\Observers;

use App\Models\MaterialTransferInvoice;

class MaterialTransferInvoiceObserver
{
    /**
     * Handle the material transfer invoice "created" event.
     *
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return void
     */
    public function created(MaterialTransferInvoice $materialTransferInvoice)
    {
        //
    }

    /**
     * Handle the material transfer invoice "updating" event.
     *
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return void
     */
    public function updating(MaterialTransferInvoice $materialTransferInvoice)
    {
        if($materialTransferInvoice->isDirty('location_id')){
            $materialTransferInvoice->distributionItems()->forceDelete();
        }
    }

    /**
     * Handle the material transfer invoice "deleted" event.
     *
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return void
     */
    public function deleted(MaterialTransferInvoice $materialTransferInvoice)
    {
        //
    }

    /**
     * Handle the material transfer invoice "restored" event.
     *
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return void
     */
    public function restored(MaterialTransferInvoice $materialTransferInvoice)
    {
        //
    }

    /**
     * Handle the material transfer invoice "force deleted" event.
     *
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return void
     */
    public function forceDeleted(MaterialTransferInvoice $materialTransferInvoice)
    {
        //
    }
}
