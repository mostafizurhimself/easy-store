<?php

namespace App\Observers;

use App\Models\Fabric;
use App\Models\FabricPurchaseItem;

class FabricPurchaseItemObserver
{

    /**
     * Handle the purchase item fabric "saving" event.
     *
     * @param  \App\Models\FabricPurchaseItem  $fabricPurchaseItem
     * @return void
     */
    public function saving(FabricPurchaseItem $fabricPurchaseItem)
    {
        //Get the Fabric
        $fabric = Fabric::find($fabricPurchaseItem->fabricId);

        //Set the purchase amount
        $fabricPurchaseItem->purchaseRate = $fabric->rate;
        if(empty($fabricPurchaseItem->unitId)){
            $fabricPurchaseItem->unitId = $fabric->unitId;
        }
        $fabricPurchaseItem->purchaseAmount = $fabric->rate * $fabricPurchaseItem->purchaseQuantity;
    }

    /**
     * Handle the purchase item fabric "saved" event.
     *
     * @param  \App\Models\FabricPurchaseItem  $fabricPurchaseItem
     * @return void
     */
    public function saved(FabricPurchaseItem $fabricPurchaseItem)
    {
        //Update the total purchase amount
        $fabricPurchaseItem->purchaseOrder->updatePurchaseAmount();
    }

    /**
     * Handle the purchase item fabric "deleting" event.
     *
     * @param  \App\Models\FabricPurchaseItem  $fabricPurchaseItem
     * @return void
     */
    public function deleting(FabricPurchaseItem $fabricPurchaseItem)
    {
        //Update the total purchase amount
        if($fabricPurchaseItem->receiveItems()->exists()){
            throw new Exception("You can not delete it now, there are some receive items related to it.");
        }
    }

    /**
     * Handle the purchase item fabric "deleted" event.
     *
     * @param  \App\Models\FabricPurchaseItem  $fabricPurchaseItem
     * @return void
     */
    public function deleted(FabricPurchaseItem $fabricPurchaseItem)
    {
        //Update the total purchase amount
        $fabricPurchaseItem->purchaseOrder->updatePurchaseAmount();
    }

    /**
     * Handle the purchase item fabric "restored" event.
     *
     * @param  \App\Models\FabricPurchaseItem  $fabricPurchaseItem
     * @return void
     */
    public function restored(FabricPurchaseItem $fabricPurchaseItem)
    {
        //Update the total purchase amount
        $fabricPurchaseItem->purchaseOrder->updatePurchaseAmount();
    }

    /**
     * Handle the purchase item fabric "force deleted" event.
     *
     * @param  \App\Models\FabricPurchaseItem  $fabricPurchaseItem
     * @return void
     */
    public function forceDeleted(FabricPurchaseItem $fabricPurchaseItem)
    {
        //Update the total purchase amount
        $fabricPurchaseItem->purchaseOrder->updatePurchaseAmount();
    }
}
