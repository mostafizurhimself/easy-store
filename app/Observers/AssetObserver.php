<?php

namespace App\Observers;

use App\Models\Asset;
use App\Facades\Helper;

class AssetObserver
{
    /**
     * Handle the asset "creating" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function creating(Asset $asset)
    {
        $asset->quantity = $asset->openingQuantity;
    }

    /**
     * Handle the asset "saved" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function saved(Asset $asset)
    {
        if(empty($asset->code)){
            $asset->code = Helper::generateReadableId($asset->id, "AS", 5);
            $asset->save();
        }
    }

    /**
     * Handle the asset "deleted" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function deleted(Asset $asset)
    {
        //
    }

    /**
     * Handle the asset "restored" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function restored(Asset $asset)
    {
        //
    }

    /**
     * Handle the asset "force deleted" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function forceDeleted(Asset $asset)
    {
        //
    }
}
