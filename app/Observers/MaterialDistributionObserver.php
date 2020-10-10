<?php

namespace App\Observers;

use App\Models\Material;
use App\Models\MaterialDistribution;

class MaterialDistributionObserver
{
    /**
     * Handle the material distribution "saving" event.
     *
     * @param  \App\Models\MaterialDistribution  $materialDistribution
     * @return void
     */
    public function saving(MaterialDistribution $materialDistribution)
    {
        $material = Material::find($materialDistribution->materialId);
        $materialDistribution->rate = $material->rate;
        $materialDistribution->unitId = $material->unitId;
        $materialDistribution->amount = $material->rate * $materialDistribution->quantity;
    }

    /**
     * Handle the material distribution "updated" event.
     *
     * @param  \App\Models\MaterialDistribution  $materialDistribution
     * @return void
     */
    public function updated(MaterialDistribution $materialDistribution)
    {
        //
    }

    /**
     * Handle the material distribution "deleted" event.
     *
     * @param  \App\Models\MaterialDistribution  $materialDistribution
     * @return void
     */
    public function deleted(MaterialDistribution $materialDistribution)
    {
        //
    }

    /**
     * Handle the material distribution "restored" event.
     *
     * @param  \App\Models\MaterialDistribution  $materialDistribution
     * @return void
     */
    public function restored(MaterialDistribution $materialDistribution)
    {
        //
    }

    /**
     * Handle the material distribution "force deleted" event.
     *
     * @param  \App\Models\MaterialDistribution  $materialDistribution
     * @return void
     */
    public function forceDeleted(MaterialDistribution $materialDistribution)
    {
        //
    }
}
