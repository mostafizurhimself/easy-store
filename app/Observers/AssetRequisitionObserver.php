<?php

namespace App\Observers;

use App\Models\AssetRequisition;

class AssetRequisitionObserver
{
    /**
     * Handle the asset requisition "created" event.
     *
     * @param  \App\Models\AssetRequisition  $assetRequisition
     * @return void
     */
    public function created(AssetRequisition $assetRequisition)
    {
        //
    }

    /**
     * Handle the asset requisition "updating" event.
     *
     * @param  \App\Models\AssetRequisition  $assetRequisition
     * @return void
     */
    public function updating(AssetRequisition $assetRequisition)
    {
        if($assetRequisition->isDirty('receiver_id')){
            $assetRequisition->requisitionItems()->forceDelete();
        }
    }

    /**
     * Handle the asset requisition "deleted" event.
     *
     * @param  \App\Models\AssetRequisition  $assetRequisition
     * @return void
     */
    public function deleted(AssetRequisition $assetRequisition)
    {
        //
    }

    /**
     * Handle the asset requisition "restored" event.
     *
     * @param  \App\Models\AssetRequisition  $assetRequisition
     * @return void
     */
    public function restored(AssetRequisition $assetRequisition)
    {
        //
    }

    /**
     * Handle the asset requisition "force deleted" event.
     *
     * @param  \App\Models\AssetRequisition  $assetRequisition
     * @return void
     */
    public function forceDeleted(AssetRequisition $assetRequisition)
    {
        //
    }
}
