<?php

namespace App\Observers;

use Exception;
use App\Models\Fabric;
use App\Facades\Settings;
use App\Models\FabricTransferItem;

class FabricTransferItemObserver
{
    /**
     * Handle the fabric transfer item "saving" event.
     *
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return void
     */
    public function saving(FabricTransferItem $fabricTransferItem)
    {
        if($fabricTransferItem->invoice->transferItems()->count() > Settings::maxInvoiceItem()){
            throw new Exception('Maximum item exceeded.');
        }
         //Get the fabric
         $fabric = Fabric::find($fabricTransferItem->fabricId);

         //Set the transfer amount
         $fabricTransferItem->transferRate = $fabric->rate;
         if(empty($fabricTransferItem->unitId)){
             $fabricTransferItem->unitId = $fabric->unitId;
         }
         $fabricTransferItem->transferAmount = $fabricTransferItem->transferRate * $fabricTransferItem->transferQuantity;
    }

    /**
     * Handle the fabric transfer item "saved" event.
     *
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return void
     */
    public function saved(FabricTransferItem $fabricTransferItem)
    {
        //Update the total transfer amount
        $fabricTransferItem->invoice->updateTransferAmount();
    }

    /**
     * Handle the fabric transfer item "deleted" event.
     *
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return void
     */
    public function deleted(FabricTransferItem $fabricTransferItem)
    {
        //Update the total transfer amount
        $fabricTransferItem->invoice->updateTransferAmount();
    }

    /**
     * Handle the fabric transfer item "restored" event.
     *
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return void
     */
    public function restored(FabricTransferItem $fabricTransferItem)
    {
        //Update the total transfer amount
        $fabricTransferItem->invoice->updateTransferAmount();
    }

    /**
     * Handle the fabric transfer item "force deleted" event.
     *
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return void
     */
    public function forceDeleted(FabricTransferItem $fabricTransferItem)
    {
        //Update the total transfer amount
        $fabricTransferItem->invoice->updateTransferAmount();
    }
}
