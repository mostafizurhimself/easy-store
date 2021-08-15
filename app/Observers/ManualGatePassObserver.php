<?php

namespace App\Observers;

use App\Models\ManualGatePass;

class ManualGatePassObserver
{
    /**
     * Handle the ManualGatePass "saving" event.
     *
     * @param  \App\Models\ManualGatePass  $manualGatePass
     * @return void
     */
    public function saving(ManualGatePass $manualGatePass)
    {
        $manualGatePass->totalQuantity = 0;
        // Set total quantity
        foreach ($manualGatePass->items as $item) {
            $manualGatePass->totalQuantity += $item['quantity'];
        }
    }

    /**
     * Handle the ManualGatePass "updated" event.
     *
     * @param  \App\Models\ManualGatePass  $manualGatePass
     * @return void
     */
    public function updated(ManualGatePass $manualGatePass)
    {
        //
    }

    /**
     * Handle the ManualGatePass "deleted" event.
     *
     * @param  \App\Models\ManualGatePass  $manualGatePass
     * @return void
     */
    public function deleted(ManualGatePass $manualGatePass)
    {
        //
    }

    /**
     * Handle the ManualGatePass "restored" event.
     *
     * @param  \App\Models\ManualGatePass  $manualGatePass
     * @return void
     */
    public function restored(ManualGatePass $manualGatePass)
    {
        //
    }

    /**
     * Handle the ManualGatePass "force deleted" event.
     *
     * @param  \App\Models\ManualGatePass  $manualGatePass
     * @return void
     */
    public function forceDeleted(ManualGatePass $manualGatePass)
    {
        //
    }
}