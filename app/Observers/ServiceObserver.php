<?php

namespace App\Observers;

use App\Facades\Helper;
use App\Models\Service;

class ServiceObserver
{
     /**
     * Handle the service "saved" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function saved(Service $service)
    {
        if(empty($service->code)){
            $service->code = Helper::generateReadableId($service->id, "SV", 5);
            $service->save();
        }
    }


    /**
     * Handle the service "created" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function created(Service $service)
    {
        //
    }

    /**
     * Handle the service "updated" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function updated(Service $service)
    {
        //
    }

    /**
     * Handle the service "deleted" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function deleted(Service $service)
    {
        //
    }

    /**
     * Handle the service "restored" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function restored(Service $service)
    {
        //
    }

    /**
     * Handle the service "force deleted" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function forceDeleted(Service $service)
    {
        //
    }
}
