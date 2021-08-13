<?php

namespace App\Observers;

use App\Models\GiftGatePass;

class GiftGatePassObserver
{
    /**
     * Handle the GiftGatePass "saving" event.
     *
     * @param  \App\Models\GiftGatePass  $giftGatePass
     * @return void
     */
    public function saving(GiftGatePass $giftGatePass)
    {
        $giftGatePass->total = $giftGatePass->tshirt + $giftGatePass->poloTshirt + $giftGatePass->shirt + $giftGatePass->gabardinePant + $giftGatePass->panjabi + $giftGatePass->kabli;
    }

    /**
     * Handle the GiftGatePass "updated" event.
     *
     * @param  \App\Models\GiftGatePass  $giftGatePass
     * @return void
     */
    public function updated(GiftGatePass $giftGatePass)
    {
        //
    }

    /**
     * Handle the GiftGatePass "deleted" event.
     *
     * @param  \App\Models\GiftGatePass  $giftGatePass
     * @return void
     */
    public function deleted(GiftGatePass $giftGatePass)
    {
        //
    }

    /**
     * Handle the GiftGatePass "restored" event.
     *
     * @param  \App\Models\GiftGatePass  $giftGatePass
     * @return void
     */
    public function restored(GiftGatePass $giftGatePass)
    {
        //
    }

    /**
     * Handle the GiftGatePass "force deleted" event.
     *
     * @param  \App\Models\GiftGatePass  $giftGatePass
     * @return void
     */
    public function forceDeleted(GiftGatePass $giftGatePass)
    {
        //
    }
}