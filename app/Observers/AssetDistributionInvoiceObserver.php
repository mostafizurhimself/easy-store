<?php

namespace App\Observers;

use App\Models\AssetDistributionInvoice;

class AssetDistributionInvoiceObserver
{
    /**
     * Handle the asset distribution invoice "created" event.
     *
     * @param  \App\Models\AssetDistributionInvoice  $assetDistributionInvoice
     * @return void
     */
    public function created(AssetDistributionInvoice $assetDistributionInvoice)
    {
        //
    }

    /**
     * Handle the asset distribution invoice "updating" event.
     *
     * @param  \App\Models\AssetDistributionInvoice  $assetDistributionInvoice
     * @return void
     */
    public function updating(AssetDistributionInvoice $assetDistributionInvoice)
    {
        if($assetDistributionInvoice->isDirty('location_id') || $assetDistributionInvoice->isDirty('requisition_id')){
            $assetDistributionInvoice->distributionItems()->forceDelete();
        }
    }

    /**
     * Handle the asset distribution invoice "deleted" event.
     *
     * @param  \App\Models\AssetDistributionInvoice  $assetDistributionInvoice
     * @return void
     */
    public function deleted(AssetDistributionInvoice $assetDistributionInvoice)
    {
        //
    }

    /**
     * Handle the asset distribution invoice "restored" event.
     *
     * @param  \App\Models\AssetDistributionInvoice  $assetDistributionInvoice
     * @return void
     */
    public function restored(AssetDistributionInvoice $assetDistributionInvoice)
    {
        //
    }

    /**
     * Handle the asset distribution invoice "force deleted" event.
     *
     * @param  \App\Models\AssetDistributionInvoice  $assetDistributionInvoice
     * @return void
     */
    public function forceDeleted(AssetDistributionInvoice $assetDistributionInvoice)
    {
        //
    }
}
