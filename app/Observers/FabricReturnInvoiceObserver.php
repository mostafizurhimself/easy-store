<?php

namespace App\Observers;

use App\Models\FabricReturnInvoice;

class FabricReturnInvoiceObserver
{
    /**
     * Handle the fabric return invoice "created" event.
     *
     * @param  \App\Models\FabricReturnInvoice  $fabricReturnInvoice
     * @return void
     */
    public function created(FabricReturnInvoice $fabricReturnInvoice)
    {
        //
    }

    /**
     * Handle the fabric return invoice "updating" event.
     *
     * @param  \App\Models\FabricReturnInvoice  $fabricReturnInvoice
     * @return void
     */
    public function updating(FabricReturnInvoice $fabricReturnInvoice)
    {
        if($fabricReturnInvoice->isDirty('location_id') || $fabricReturnInvoice->isDirty('supplier_id')){
            $fabricReturnInvoice->returnItems()->forceDelete();
        }
    }

    /**
     * Handle the fabric return invoice "deleted" event.
     *
     * @param  \App\Models\FabricReturnInvoice  $fabricReturnInvoice
     * @return void
     */
    public function deleted(FabricReturnInvoice $fabricReturnInvoice)
    {
        //
    }

    /**
     * Handle the fabric return invoice "restored" event.
     *
     * @param  \App\Models\FabricReturnInvoice  $fabricReturnInvoice
     * @return void
     */
    public function restored(FabricReturnInvoice $fabricReturnInvoice)
    {
        //
    }

    /**
     * Handle the fabric return invoice "force deleted" event.
     *
     * @param  \App\Models\FabricReturnInvoice  $fabricReturnInvoice
     * @return void
     */
    public function forceDeleted(FabricReturnInvoice $fabricReturnInvoice)
    {
        //
    }
}
