<?php

namespace App\Observers;

use Exception;
use App\Models\Material;
use App\Facades\Settings;
use App\Models\MaterialPurchaseItem;

class MaterialPurchaseItemObserver
{

    /**
     * Handle the material purchase item "saving" event.
     *
     * @param  \App\Models\MaterialPurchaseItem  $materialPurchaseItem
     * @return void
     */
    public function saving(MaterialPurchaseItem $materialPurchaseItem)
    {
        if($materialPurchaseItem->purchaseOrder->purchaseItems()->count() > Settings::maxInvoiceItem()){
            throw new Exception('Maximum item exceeded.');
        }
        //Get the material
        $material = Material::find($materialPurchaseItem->materialId);

        //Set the purchase amount
        $materialPurchaseItem->purchaseRate = $material->rate;
        if(empty($materialPurchaseItem->unitId)){
            $materialPurchaseItem->unitId = $material->unitId;
        }
        $materialPurchaseItem->purchaseAmount =  $materialPurchaseItem->purchaseRate * $materialPurchaseItem->purchaseQuantity;
    }

    /**
     * Handle the material purchase item "saved" event.
     *
     * @param  \App\Models\MaterialPurchaseItem  $materialPurchaseItem
     * @return void
     */
    public function saved(MaterialPurchaseItem $materialPurchaseItem)
    {
        //Update the total purchase amount
        $materialPurchaseItem->purchaseOrder->updatePurchaseAmount();
    }

    /**
     * Handle the material purchase item "deleting" event.
     *
     * @param  \App\Models\MaterialPurchaseItem  $materialPurchaseItem
     * @return void
     */
    public function deleting(MaterialPurchaseItem $materialPurchaseItem)
    {
        //Update the total purchase amount
        if($materialPurchaseItem->receiveItems()->exists()){
            throw new Exception("You can not delete it now, there are some receive items related to it.");
        }
    }

    /**
     * Handle the material purchase item "deleted" event.
     *
     * @param  \App\Models\MaterialPurchaseItem  $materialPurchaseItem
     * @return void
     */
    public function deleted(MaterialPurchaseItem $materialPurchaseItem)
    {
        //Update the total purchase amount
        $materialPurchaseItem->purchaseOrder->updatePurchaseAmount();
    }

    /**
     * Handle the material purchase item "restored" event.
     *
     * @param  \App\Models\MaterialPurchaseItem  $materialPurchaseItem
     * @return void
     */
    public function restored(MaterialPurchaseItem $materialPurchaseItem)
    {
        //Update the total purchase amount
        $materialPurchaseItem->purchaseOrder->updatePurchaseAmount();
    }

    /**
     * Handle the material purchase item "force deleted" event.
     *
     * @param  \App\Models\MaterialPurchaseItem  $materialPurchaseItem
     * @return void
     */
    public function forceDeleted(MaterialPurchaseItem $materialPurchaseItem)
    {
        //Update the total purchase amount
        $materialPurchaseItem->purchaseOrder->updatePurchaseAmount();
    }
}
