<?php

namespace App\Observers;

use Exception;
use App\Models\Material;
use App\Facades\Settings;
use App\Models\MaterialTransferItem;

class MaterialTransferItemObserver
{
    /**
     * Handle the material transfer item "saving" event.
     *
     * @param  \App\Models\MaterialTransferItem  $materialTransferItem
     * @return void
     */
    public function saving(MaterialTransferItem $materialTransferItem)
    {
        if($materialTransferItem->invoice->transferItems()->count() > Settings::maxInvoiceItem()){
            throw new Exception('Maximum item exceeded.');
        }

        //Get the material
        $material = Material::find($materialTransferItem->materialId);

        //Set the transfer amount
        $materialTransferItem->transferRate = $material->rate;
        if(empty($materialTransferItem->unitId)){
            $materialTransferItem->unitId = $material->unitId;
        }
        $materialTransferItem->transferAmount = $material->rate * $materialTransferItem->transferQuantity;
    }

    /**
     * Handle the material transfer item "saved" event.
     *
     * @param  \App\Models\MaterialTransferItem  $materialTransferItem
     * @return void
     */
    public function saved(MaterialTransferItem $materialTransferItem)
    {
        //Update the total transfer amount
        $materialTransferItem->invoice->updateTransferAmount();
    }

    /**
     * Handle the material transfer item "deleted" event.
     *
     * @param  \App\Models\MaterialTransferItem  $materialTransferItem
     * @return void
     */
    public function deleted(MaterialTransferItem $materialTransferItem)
    {
        //Update the total transfer amount
        $materialTransferItem->invoice->updateTransferAmount();
    }

    /**
     * Handle the material transfer item "restored" event.
     *
     * @param  \App\Models\MaterialTransferItem  $materialTransferItem
     * @return void
     */
    public function restored(MaterialTransferItem $materialTransferItem)
    {
        //Update the total transfer amount
        $materialTransferItem->invoice->updateTransferAmount();
    }

    /**
     * Handle the material transfer item "force deleted" event.
     *
     * @param  \App\Models\MaterialTransferItem  $materialTransferItem
     * @return void
     */
    public function forceDeleted(MaterialTransferItem $materialTransferItem)
    {
        //Update the total transfer amount
        $materialTransferItem->invoice->updateTransferAmount();
    }
}
