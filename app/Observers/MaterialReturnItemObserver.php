<?php

namespace App\Observers;

use App\Models\Material;
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
        //Get the material
        $material = Material::find($materialReturnItem->materialId);

        //Set the amount
        if (empty($materialReturnItem->rate)) {
            $materialReturnItem->rate = $material->rate;
        }
        $materialReturnItem->amount = $material->rate * $materialReturnItem->quantity;
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
