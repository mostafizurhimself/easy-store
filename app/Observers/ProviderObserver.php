<?php

namespace App\Observers;

use App\Models\Provider;

class ProviderObserver
{
    /**
     * Handle the provider "creating" event.
     *
     * @param  \App\Models\Provider  $provider
     * @return void
     */
    public function creating(Provider $provider)
    {
        $provider->balance = $provider->openingBalance;
    }

    /**
     * Handle the provider "updated" event.
     *
     * @param  \App\Models\Provider  $provider
     * @return void
     */
    public function updated(Provider $provider)
    {
        //
    }

    /**
     * Handle the provider "deleted" event.
     *
     * @param  \App\Models\Provider  $provider
     * @return void
     */
    public function deleted(Provider $provider)
    {
        //
    }

    /**
     * Handle the provider "restored" event.
     *
     * @param  \App\Models\Provider  $provider
     * @return void
     */
    public function restored(Provider $provider)
    {
        //
    }

    /**
     * Handle the provider "force deleted" event.
     *
     * @param  \App\Models\Provider  $provider
     * @return void
     */
    public function forceDeleted(Provider $provider)
    {
        //
    }
}
