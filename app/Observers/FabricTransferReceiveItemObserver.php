<?php

namespace App\Observers;

use App\Models\FabricTransferReceiveItem;

class FabricTransferReceiveItemObserver
{
    /**
     * Handle the fabric transfer receive item "created" event.
     *
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return void
     */
    public function created(FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        //
    }

    /**
     * Handle the fabric transfer receive item "updated" event.
     *
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return void
     */
    public function updated(FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        //
    }

    /**
     * Handle the fabric transfer receive item "deleted" event.
     *
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return void
     */
    public function deleted(FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        //
    }

    /**
     * Handle the fabric transfer receive item "restored" event.
     *
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return void
     */
    public function restored(FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        //
    }

    /**
     * Handle the fabric transfer receive item "force deleted" event.
     *
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return void
     */
    public function forceDeleted(FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        //
    }
}
