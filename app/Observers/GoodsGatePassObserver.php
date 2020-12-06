<?php

namespace App\Observers;

use App\Models\GoodsGatePass;

class GoodsGatePassObserver
{
    /**
     * Handle the GoodsGatePass "saving" event.
     *
     * @param  \App\Models\GoodsGatePass  $goodsGatePass
     * @return void
     */
    public function saving(GoodsGatePass $goodsGatePass)
    {
        $goodsGatePass->locationId = $goodsGatePass->invoice->locationId;
    }

    /**
     * Handle the GoodsGatePass "saved" event.
     *
     * @param  \App\Models\GoodsGatePass  $goodsGatePass
     * @return void
     */
    public function saved(GoodsGatePass $goodsGatePass)
    {
        //
    }

    /**
     * Handle the GoodsGatePass "deleted" event.
     *
     * @param  \App\Models\GoodsGatePass  $goodsGatePass
     * @return void
     */
    public function deleted(GoodsGatePass $goodsGatePass)
    {
        //
    }

    /**
     * Handle the GoodsGatePass "restored" event.
     *
     * @param  \App\Models\GoodsGatePass  $goodsGatePass
     * @return void
     */
    public function restored(GoodsGatePass $goodsGatePass)
    {
        //
    }

    /**
     * Handle the GoodsGatePass "force deleted" event.
     *
     * @param  \App\Models\GoodsGatePass  $goodsGatePass
     * @return void
     */
    public function forceDeleted(GoodsGatePass $goodsGatePass)
    {
        //
    }
}
