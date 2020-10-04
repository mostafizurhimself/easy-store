<?php

namespace App\Observers;

use App\Models\ServiceInvoice;

class ServiceInvoiceObserver
{
    /**
     * Handle the service invoice "created" event.
     *
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return void
     */
    public function created(ServiceInvoice $serviceInvoice)
    {
        //
    }

    /**
     * Handle the service invoice "updating" event.
     *
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return void
     */
    public function updating(ServiceInvoice $serviceInvoice)
    {
        if($serviceInvoice->isDirty('location_id') || $serviceInvoice->isDirty('provider_id')){
            $serviceInvoice->dispatches()->forceDelete();
        }
    }

    /**
     * Handle the service invoice "deleted" event.
     *
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return void
     */
    public function deleted(ServiceInvoice $serviceInvoice)
    {
        //
    }

    /**
     * Handle the service invoice "restored" event.
     *
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return void
     */
    public function restored(ServiceInvoice $serviceInvoice)
    {
        //
    }

    /**
     * Handle the service invoice "force deleted" event.
     *
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return void
     */
    public function forceDeleted(ServiceInvoice $serviceInvoice)
    {
        //
    }
}
