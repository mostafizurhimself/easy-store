<?php

namespace App\Observers;

use App\Facades\Helper;
use App\Models\Material;

class MaterialObserver
{
    /**
     * Handle the Material "creating" event.
     *
     * @param  \App\Models\Material  $material
     * @return void
     */
    public function creating(Material $material)
    {
        $material->quantity = $material->openingQuantity;
    }

    /**
     * Handle the Material "saved" event.
     *
     * @param  \App\Models\Material  $material
     * @return void
     */
    public function saved(Material $material)
    {
        if(empty($material->code)){
            $material->code = Helper::generateReadableId($material->id, "MR", 5);
            $material->save();
        }
    }

    /**
     * Handle the Material "updated" event.
     *
     * @param  \App\Models\Material  $material
     * @return void
     */
    public function updated(Material $material)
    {
        //
    }

    /**
     * Handle the Material "deleted" event.
     *
     * @param  \App\Models\Material  $material
     * @return void
     */
    public function deleted(Material $material)
    {
        //
    }

    /**
     * Handle the Material "restored" event.
     *
     * @param  \App\Models\Material  $material
     * @return void
     */
    public function restored(Material $material)
    {
        //
    }

    /**
     * Handle the Material "force deleted" event.
     *
     * @param  \App\Models\Material  $material
     * @return void
     */
    public function forceDeleted(Material $material)
    {
        //
    }
}
