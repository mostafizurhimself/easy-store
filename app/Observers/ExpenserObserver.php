<?php

namespace App\Observers;

use App\Facades\Helper;
use App\Models\Expenser;

class ExpenserObserver
{
    /**
     * Handle the expenser "creating" event.
     *
     * @param  \App\Models\Expenser  $expenser
     * @return void
     */
    public function creating(Expenser $expenser)
    {
        $expenser->balance = $expenser->openingBalance;
    }

    /**
     * Handle the expenser "saved" event.
     *
     * @param  \App\Models\Expenser  $expenser
     * @return void
     */
    public function saved(Expenser $expenser)
    {
        if(empty($expenser->code)){
            $expenser->code = Helper::generateReadableId($expenser->id, "EXP", 3);
            $expenser->save();
        }
    }

    /**
     * Handle the expenser "deleted" event.
     *
     * @param  \App\Models\Expenser  $expenser
     * @return void
     */
    public function deleted(Expenser $expenser)
    {
        //
    }

    /**
     * Handle the expenser "restored" event.
     *
     * @param  \App\Models\Expenser  $expenser
     * @return void
     */
    public function restored(Expenser $expenser)
    {
        //
    }

    /**
     * Handle the expenser "force deleted" event.
     *
     * @param  \App\Models\Expenser  $expenser
     * @return void
     */
    public function forceDeleted(Expenser $expenser)
    {
        //
    }
}
