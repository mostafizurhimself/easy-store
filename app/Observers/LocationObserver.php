<?php

namespace App\Observers;

use App\Models\Location;
use Illuminate\Support\Facades\Cache;

class LocationObserver
{
    /**
     * Handle the Location "created" event.
     *
     * @param  \App\Models\Location  $location
     * @return void
     */
    public function created(Location $location)
    {
        Cache::forget('nova-location-filter-options');
        Cache::forget('nova-location-belongs-to-filter-options');
    }

    /**
     * Handle the Location "updated" event.
     *
     * @param  \App\Models\Location  $location
     * @return void
     */
    public function updated(Location $location)
    {
        Cache::forget('nova-location-filter-options');
        Cache::forget('nova-location-belongs-to-filter-options');
    }

    /**
     * Handle the Location "deleted" event.
     *
     * @param  \App\Models\Location  $location
     * @return void
     */
    public function deleted(Location $location)
    {
        Cache::forget('nova-location-filter-options');
        Cache::forget('nova-location-belongs-to-filter-options');
    }

    /**
     * Handle the Location "restored" event.
     *
     * @param  \App\Models\Location  $location
     * @return void
     */
    public function restored(Location $location)
    {
        Cache::forget('nova-location-filter-options');
        Cache::forget('nova-location-belongs-to-filter-options');
    }

    /**
     * Handle the Location "force deleted" event.
     *
     * @param  \App\Models\Location  $location
     * @return void
     */
    public function forceDeleted(Location $location)
    {
        Cache::forget('nova-location-filter-options');
        Cache::forget('nova-location-belongs-to-filter-options');
    }
}