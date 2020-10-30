<?php

namespace App\Observers;

use Exception;
use App\Models\Fabric;
use App\Facades\Settings;
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
        if($fabricReturnItem->invoice->returnItems()->count() > Settings::maxInvoiceItem()){
            throw new Exception('Maximum item exceeded.');
        }
        //Get the Fabric
        $fabric = Fabric::find($fabricReturnItem->fabricId);

        //Set the amount
        if(empty($fabricReturnItem->rate)){
            $fabricReturnItem->rate = $fabric->rate;
        }
        $fabricReturnItem->unitId = $fabric->unitId;
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
