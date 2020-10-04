<?php

namespace App\Observers;

use App\Models\AssetReturnInvoice;

class AssetReturnInvoiceObserver
{
    /**
     * Handle the asset return invoice "created" event.
     *
     * @param  \App\Models\AssetReturnInvoice  $assetReturnInvoice
     * @return void
     */
    public function created(AssetReturnInvoice $assetReturnInvoice)
    {
        //
    }

    /**
     * Handle the asset return invoice "updating" event.
     *
     * @param  \App\Models\AssetReturnInvoice  $assetReturnInvoice
     * @return void
     */
    public function updating(AssetReturnInvoice $assetReturnInvoice)
    {
        if($assetReturnInvoice->isDirty('location_id') || $assetReturnInvoice->isDirty('supplier_id')){
            $assetReturnInvoice->returnItems()->forceDelete();
        }
    }

    /**
     * Handle the asset return invoice "deleted" event.
     *
     * @param  \App\Models\AssetReturnInvoice  $assetReturnInvoice
     * @return void
     */
    public function deleted(AssetReturnInvoice $assetReturnInvoice)
    {
        //
    }

    /**
     * Handle the asset return invoice "restored" event.
     *
     * @param  \App\Models\AssetReturnInvoice  $assetReturnInvoice
     * @return void
     */
    public function restored(AssetReturnInvoice $assetReturnInvoice)
    {
        //
    }

    /**
     * Handle the asset return invoice "force deleted" event.
     *
     * @param  \App\Models\AssetReturnInvoice  $assetReturnInvoice
     * @return void
     */
    public function forceDeleted(AssetReturnInvoice $assetReturnInvoice)
    {
        //
    }
}
