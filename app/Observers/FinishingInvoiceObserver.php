<?php

namespace App\Observers;

use App\Models\FinishingInvoice;

class FinishingInvoiceObserver
{
    /**
     * Handle the finishing invoice "created" event.
     *
     * @param  \App\Models\FinishingInvoice  $finishingInvoice
     * @return void
     */
    public function created(FinishingInvoice $finishingInvoice)
    {
        //
    }

    /**
     * Handle the finishing invoice "updating" event.
     *
     * @param  \App\Models\FinishingInvoice  $finishingInvoice
     * @return void
     */
    public function updating(FinishingInvoice $finishingInvoice)
    {
        if($finishingInvoice->isDirty('location_id')){
            $finishingInvoice->finishings()->forceDelete();
        }
    }

    /**
     * Handle the finishing invoice "deleting" event.
     *
     * @param  \App\Models\FinishingInvoice  $finishingInvoice
     * @return void
     */
    public function deleting(FinishingInvoice $finishingInvoice)
    {
        if($finishingInvoice->isForceDeleting()){
            $finishingInvoice->finishings()->forceDelete();
        }else{
            $finishingInvoice->finishings()->delete();
        }
    }

    /**
     * Handle the finishing invoice "restored" event.
     *
     * @param  \App\Models\FinishingInvoice  $finishingInvoice
     * @return void
     */
    public function restored(FinishingInvoice $finishingInvoice)
    {
        $finishingInvoice->finishings()->restore();
    }

    /**
     * Handle the finishing invoice "force deleted" event.
     *
     * @param  \App\Models\FinishingInvoice  $finishingInvoice
     * @return void
     */
    public function forceDeleted(FinishingInvoice $finishingInvoice)
    {
        //
    }
}
