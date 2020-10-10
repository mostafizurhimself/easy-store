<?php

namespace App\Observers;

use App\Models\Service;
use App\Models\ServiceDispatch;

class ServiceDispatchObserver
{
    /**
     * Handle the service dispatch "saving" event.
     *
     * @param  \App\Models\ServiceDispatch  $serviceDispatch
     * @return void
     */
    public function saving(ServiceDispatch $serviceDispatch)
    {
        //Get the service
        $service = Service::find($serviceDispatch->serviceId);

        //Set the dispatch amount
        $serviceDispatch->rate = $service->rate;
        $serviceDispatch->unitId = $service->unitId;
        $serviceDispatch->dispatchAmount = $service->rate * $serviceDispatch->dispatchQuantity;
    }

    /**
     * Handle the service dispatch "saved" event.
     *
     * @param  \App\Models\ServiceDispatch  $serviceDispatch
     * @return void
     */
    public function saved(ServiceDispatch $serviceDispatch)
    {
        //Update the total amount of invoice
        $serviceDispatch->invoice->updateDispatchAmount();
    }

    /**
     * Handle the service dispatch "deleted" event.
     *
     * @param  \App\Models\ServiceDispatch  $serviceDispatch
     * @return void
     */
    public function deleted(ServiceDispatch $serviceDispatch)
    {
        //Update the total amount of invoice
        $serviceDispatch->invoice->updateDispatchAmount();
    }

    /**
     * Handle the service dispatch "restored" event.
     *
     * @param  \App\Models\ServiceDispatch  $serviceDispatch
     * @return void
     */
    public function restored(ServiceDispatch $serviceDispatch)
    {
        //Update the total amount of invoice
        $serviceDispatch->invoice->updateDispatchAmount();
    }

    /**
     * Handle the service dispatch "force deleted" event.
     *
     * @param  \App\Models\ServiceDispatch  $serviceDispatch
     * @return void
     */
    public function forceDeleted(ServiceDispatch $serviceDispatch)
    {
        //Update the total amount of invoice
        $serviceDispatch->invoice->updateDispatchAmount();
    }
}
