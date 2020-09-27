<?php

namespace App\Observers;

use App\Models\FabricReturnItem;

class FabricReturnItemObserver
{
    /**
     * Handle the fabric return item "created" event.
     *
     * @param  \App\Models\FabricReturnItem  $fabricReturnItem
     * @return void
     */
    public function created(FabricReturnItem $fabricReturnItem)
    {
        //
    }

    /**
     * Handle the fabric return item "updated" event.
     *
     * @param  \App\Models\FabricReturnItem  $fabricReturnItem
     * @return void
     */
    public function updated(FabricReturnItem $fabricReturnItem)
    {
        //
    }

    /**
     * Handle the fabric return item "deleted" event.
     *
     * @param  \App\Models\FabricReturnItem  $fabricReturnItem
     * @return void
     */
    public function deleted(FabricReturnItem $fabricReturnItem)
    {
        //
    }

    /**
     * Handle the fabric return item "restored" event.
     *
     * @param  \App\Models\FabricReturnItem  $fabricReturnItem
     * @return void
     */
    public function restored(FabricReturnItem $fabricReturnItem)
    {
        //
    }

    /**
     * Handle the fabric return item "force deleted" event.
     *
     * @param  \App\Models\FabricReturnItem  $fabricReturnItem
     * @return void
     */
    public function forceDeleted(FabricReturnItem $fabricReturnItem)
    {
        //
    }
}
