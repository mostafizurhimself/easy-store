<?php

namespace App\Observers;

use App\Models\Unit;
use Illuminate\Support\Facades\Cache;

class UnitObserver
{
    /**
     * Handle the Unit "created" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function created(Unit $unit)
    {
        Cache::forget('nova-unit-select-options');
    }

    /**
     * Handle the Unit "updated" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function updated(Unit $unit)
    {
        Cache::forget('nova-unit-select-options');
    }

    /**
     * Handle the Unit "deleted" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function deleted(Unit $unit)
    {
        Cache::forget('nova-unit-select-options');
    }

    /**
     * Handle the Unit "restored" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function restored(Unit $unit)
    {
        Cache::forget('nova-unit-select-options');
    }

    /**
     * Handle the Unit "force deleted" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function forceDeleted(Unit $unit)
    {
        Cache::forget('nova-unit-select-options');
    }
}