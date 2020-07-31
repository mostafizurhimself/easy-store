<?php

namespace App\Observers;

use App\Models\Fabric;
use App\Facades\Helper;

class FabricObserver
{
    /**
     * Handle the fabric "creating" event.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return void
     */
    public function creating(Fabric $fabric)
    {
        $fabric->quantity = $fabric->openingQuantity;
    }

    /**
     * Handle the fabric "created" event.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return void
     */
    public function saved(Fabric $fabric)
    {
        if(empty($fabric->code)){
            $fabric->code = Helper::generateReadableId($fabric->id, "FB", 5);
            $fabric->save();
        }
    }

    /**
     * Handle the fabric "updated" event.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return void
     */
    public function updated(Fabric $fabric)
    {
        //
    }

    /**
     * Handle the fabric "deleted" event.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return void
     */
    public function deleted(Fabric $fabric)
    {
        //
    }

    /**
     * Handle the fabric "restored" event.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return void
     */
    public function restored(Fabric $fabric)
    {
        //
    }

    /**
     * Handle the fabric "force deleted" event.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return void
     */
    public function forceDeleted(Fabric $fabric)
    {
        //
    }
}
