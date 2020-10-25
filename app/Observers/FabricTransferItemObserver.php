<?php

namespace App\Observers;

use App\Models\FabricTransferItem;

class FabricTransferItemObserver
{
    /**
     * Handle the fabric transfer item "created" event.
     *
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return void
     */
    public function created(FabricTransferItem $fabricTransferItem)
    {
        //
    }

    /**
     * Handle the fabric transfer item "updated" event.
     *
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return void
     */
    public function updated(FabricTransferItem $fabricTransferItem)
    {
        //
    }

    /**
     * Handle the fabric transfer item "deleted" event.
     *
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return void
     */
    public function deleted(FabricTransferItem $fabricTransferItem)
    {
        //
    }

    /**
     * Handle the fabric transfer item "restored" event.
     *
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return void
     */
    public function restored(FabricTransferItem $fabricTransferItem)
    {
        //
    }

    /**
     * Handle the fabric transfer item "force deleted" event.
     *
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return void
     */
    public function forceDeleted(FabricTransferItem $fabricTransferItem)
    {
        //
    }
}
