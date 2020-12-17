<?php

namespace App\Observers;

use App\Models\MaterialReturnInvoice;

class MaterialReturnInvoiceObserver
{
    /**
     * Handle the material return invoice "created" event.
     *
     * @param  \App\Models\MaterialReturnInvoice  $materialReturnInvoice
     * @return void
     */
    public function created(MaterialReturnInvoice $materialReturnInvoice)
    {
        //
    }

    /**
     * Handle the material return invoice "updating" event.
     *
     * @param  \App\Models\MaterialReturnInvoice  $materialReturnInvoice
     * @return void
     */
    public function updating(MaterialReturnInvoice $materialReturnInvoice)
    {
        if ($materialReturnInvoice->isDirty('location_id') || $materialReturnInvoice->isDirty('supplier_id')) {
            $materialReturnInvoice->returnItems()->forceDelete();
        }
    }

    /**
     * Handle the material return invoice "deleting" event.
     *
     * @param  \App\Models\MaterialReturnInvoice  $materialReturnInvoice
     * @return void
     */
    public function deleting(MaterialReturnInvoice $materialReturnInvoice)
    {
        // Delete if force deleted
        if ($materialReturnInvoice->isForceDeleting()) {
            // Force Delete related return items
            $materialReturnInvoice->returnItems()->forceDelete();
        } else {
            // Delete related return items
            $materialReturnInvoice->returnItems()->delete();
        }
    }

    /**
     * Handle the material return invoice "restored" event.
     *
     * @param  \App\Models\MaterialReturnInvoice  $materialReturnInvoice
     * @return void
     */
    public function restored(MaterialReturnInvoice $materialReturnInvoice)
    {
        // Restore deleted items
        $materialReturnInvoice->returnItems()->restore();
    }

    /**
     * Handle the material return invoice "force deleted" event.
     *
     * @param  \App\Models\MaterialReturnInvoice  $materialReturnInvoice
     * @return void
     */
    public function forceDeleted(MaterialReturnInvoice $materialReturnInvoice)
    {
        //
    }
}
