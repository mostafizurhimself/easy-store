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
        if ($materialTransferInvoice->isDirty('location_id')) {
            $materialTransferInvoice->transferItems()->forceDelete();
        }
    }

    /**
     * Handle the material transfer invoice "deleting" event.
     *
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return void
     */
    public function deleting(MaterialTransferInvoice $materialTransferInvoice)
    {
        if ($materialTransferInvoice->isForceDeleting()) {
            // Force Delete transfer items
            $materialTransferInvoice->transferItems()->forceDelete();

            // Force Delete receive items
            $materialTransferInvoice->receiveItems()->forceDelete();
        } else {
            // Delete transfer items
            $materialTransferInvoice->transferItems()->delete();

            // Delete receive items
            $materialTransferInvoice->receiveItems()->delete();
        }
    }

    /**
     * Handle the material transfer invoice "restored" event.
     *
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return void
     */
    public function restored(MaterialTransferInvoice $materialTransferInvoice)
    {
        // Restore transfer items
        $materialTransferInvoice->transferItems()->restore();
        // Restore receive items
        $materialTransferInvoice->receiveItems()->restore();
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
