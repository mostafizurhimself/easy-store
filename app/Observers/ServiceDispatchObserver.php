<?php

namespace App\Observers;

use Exception;
use App\Models\Service;
use App\Facades\Settings;
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
        if ($serviceDispatch->invoice->dispatches()->count() > Settings::maxInvoiceItem()) {
            throw new Exception('Maximum item exceeded.');
        }

        //Get the service
        $service = Service::find($serviceDispatch->serviceId);

        //Set the dispatch amount
        $serviceDispatch->rate = $service->rate;

        if (empty($serviceDispatch->unitId)) {
            $serviceDispatch->unitId = $service->unitId;
        }
        $serviceDispatch->dispatchAmount = $serviceDispatch->rate * $serviceDispatch->dispatchQuantity;
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
        $serviceDispatch->invoice->updateDispatchQuantity();
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
        $serviceDispatch->invoice->updateDispatchQuantity();
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
        $serviceDispatch->invoice->updateDispatchQuantity();
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
        $serviceDispatch->invoice->updateDispatchQuantity();
        $serviceDispatch->invoice->updateDispatchAmount();
    }
}