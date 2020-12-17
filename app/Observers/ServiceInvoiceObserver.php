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
        if ($serviceInvoice->isDirty('location_id') || $serviceInvoice->isDirty('provider_id')) {
            $serviceInvoice->dispatches()->forceDelete();
        }
    }

    /**
     * Handle the service invoice "deleting" event.
     *
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return void
     */
    public function deleting(ServiceInvoice $serviceInvoice)
    {
        if ($serviceInvoice->isForceDeleting()) {
            // Force Delete related dispatches
            $serviceInvoice->dispatches()->forceDelete();
            // Force Delete related receives
            $serviceInvoice->receives()->forceDelete();
        } else {
            // Delete related dispatches
            $serviceInvoice->dispatches()->delete();
            // Delete related receives
            $serviceInvoice->receives()->delete();
        }
    }

    /**
     * Handle the service invoice "restored" event.
     *
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return void
     */
    public function restored(ServiceInvoice $serviceInvoice)
    {
        // Restore related dispatches
        $serviceInvoice->dispatches()->restore();
        // Restore related receives
        $serviceInvoice->receives()->restore();
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
