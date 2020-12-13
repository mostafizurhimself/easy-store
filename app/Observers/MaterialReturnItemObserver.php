<?php

namespace App\Observers;

use Exception;
use App\Models\Material;
use App\Facades\Settings;
use App\Models\MaterialReturnItem;

class MaterialReturnItemObserver
{
    /**
     * Handle the material return item "saving" event.
     *
     * @param  \App\Models\MaterialReturnItem  $materialReturnItem
     * @return void
     */
    public function saving(MaterialReturnItem $materialReturnItem)
    {
        if($materialReturnItem->invoice->returnItems()->count() > Settings::maxInvoiceItem()){
            throw new Exception('Maximum item exceeded.');
        }

        //Get the material
        $material = Material::find($materialReturnItem->materialId);

        //Set the amount
        if (empty($materialReturnItem->rate)) {
            $materialReturnItem->rate = $material->rate;
        }
        if(empty($materialReturnItem->unitId)){
            $materialReturnItem->unitId = $material->unitId;
        }
        $materialReturnItem->amount = $materialReturnItem->rate * $materialReturnItem->quantity;
    }

    /**
     * Handle the material return item "saved" event.
     *
     * @param  \App\Models\MaterialReturnItem  $materialReturnItem
     * @return void
     */
    public function saved(MaterialReturnItem $materialReturnItem)
    {
        //Update the total return amount
        $materialReturnItem->invoice->updateReturnAmount();
    }

    /**
     * Handle the material return item "deleted" event.
     *
     * @param  \App\Models\MaterialReturnItem  $materialReturnItem
     * @return void
     */
    public function deleted(MaterialReturnItem $materialReturnItem)
    {
        //Update the total return amount
        $materialReturnItem->invoice->updateReturnAmount();
    }

    /**
     * Handle the material return item "restored" event.
     *
     * @param  \App\Models\MaterialReturnItem  $materialReturnItem
     * @return void
     */
    public function restored(MaterialReturnItem $materialReturnItem)
    {
        //Update the total return amount
        $materialReturnItem->invoice->updateReturnAmount();
    }

    /**
     * Handle the material return item "force deleted" event.
     *
     * @param  \App\Models\MaterialReturnItem  $materialReturnItem
     * @return void
     */
    public function forceDeleted(MaterialReturnItem $materialReturnItem)
    {
        //Update the total return amount
        $materialReturnItem->invoice->updateReturnAmount();
    }
}
