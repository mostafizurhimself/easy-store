<?php

namespace App\Observers;

use App\Models\Fabric;
use App\Models\FabricReturnItem;

class FabricReturnItemObserver
{
    /**
     * Handle the fabric return item "created" event.
     *
     * @param  \App\Models\FabricReturnItem  $fabricReturnItem
     * @return void
     */
    public function saving(FabricReturnItem $fabricReturnItem)
    {
        //Get the Fabric
        $fabric = Fabric::find($fabricReturnItem->fabricId);

        //Set the amount
        if(empty($fabricReturnItem->rate)){
            $fabricReturnItem->rate = $fabric->rate;
        }
        $fabricReturnItem->amount = $fabric->rate * $fabricReturnItem->quantity;
    }

    /**
     * Handle the fabric return item "updated" event.
     *
     * @param  \App\Models\FabricReturnItem  $fabricReturnItem
     * @return void
     */
    public function saved(FabricReturnItem $fabricReturnItem)
    {
        //Update the total return amount
        $fabricReturnItem->invoice->updateReturnAmount();
    }

    /**
     * Handle the fabric return item "deleted" event.
     *
     * @param  \App\Models\FabricReturnItem  $fabricReturnItem
     * @return void
     */
    public function deleted(FabricReturnItem $fabricReturnItem)
    {
        //Update the total return amount
        $fabricReturnItem->invoice->updateReturnAmount();
    }

    /**
     * Handle the fabric return item "restored" event.
     *
     * @param  \App\Models\FabricReturnItem  $fabricReturnItem
     * @return void
     */
    public function restored(FabricReturnItem $fabricReturnItem)
    {
        //Update the total return amount
        $fabricReturnItem->invoice->updateReturnAmount();
    }

    /**
     * Handle the fabric return item "force deleted" event.
     *
     * @param  \App\Models\FabricReturnItem  $fabricReturnItem
     * @return void
     */
    public function forceDeleted(FabricReturnItem $fabricReturnItem)
    {
        //Update the total return amount
        $fabricReturnItem->invoice->updateReturnAmount();
    }
}
