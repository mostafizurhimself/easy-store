<?php

namespace App\Observers;

use App\Models\Fabric;
use App\Models\FabricDistribution;

class FabricDistributionObserver
{
    /**
     * Handle the fabric distribution "saving" event.
     *
     * @param  \App\Models\FabricDistribution  $fabricDistribution
     * @return void
     */
    public function saving(FabricDistribution $fabricDistribution)
    {
        $fabric = Fabric::find($fabricDistribution->fabricId);
        $fabricDistribution->rate = $fabric->rate;
        $fabricDistribution->unitId = $fabric->unitId;
        $fabricDistribution->amount = $fabric->rate * $fabricDistribution->quantity;
    }

    /**
     * Handle the fabric distribution "updated" event.
     *
     * @param  \App\Models\FabricDistribution  $fabricDistribution
     * @return void
     */
    public function updated(FabricDistribution $fabricDistribution)
    {
        //
    }

    /**
     * Handle the fabric distribution "deleted" event.
     *
     * @param  \App\Models\FabricDistribution  $fabricDistribution
     * @return void
     */
    public function deleted(FabricDistribution $fabricDistribution)
    {
        //
    }

    /**
     * Handle the fabric distribution "restored" event.
     *
     * @param  \App\Models\FabricDistribution  $fabricDistribution
     * @return void
     */
    public function restored(FabricDistribution $fabricDistribution)
    {
        //
    }

    /**
     * Handle the fabric distribution "force deleted" event.
     *
     * @param  \App\Models\FabricDistribution  $fabricDistribution
     * @return void
     */
    public function forceDeleted(FabricDistribution $fabricDistribution)
    {
        //
    }
}
