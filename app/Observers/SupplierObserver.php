<?php

namespace App\Observers;

use App\Facades\Helper;
use App\Models\Supplier;
use App\Events\SupplierEvent;

class SupplierObserver
{
    /**
     * Handle the supplier "creating" event.
     *
     * @param  \App\Model\Supplier  $supplier
     * @return void
     */
    public function creating(Supplier $supplier)
    {
        $supplier->balance = $supplier->openingBalance;
    }

     /**
     * Handle the supplier "created" event.
     *
     * @param  \App\Model\Supplier  $supplier
     * @return void
     */
    public function created(Supplier $supplier)
    {
        // SupplierEvent::dispatch($supplier);
    }

    /**
     * Handle the supplier "updated" event.
     *
     * @param  \App\Model\Supplier  $supplier
     * @return void
     */
    public function updated(Supplier $supplier)
    {
        //
    }

    /**
     * Handle the supplier "deleted" event.
     *
     * @param  \App\Model\Supplier  $supplier
     * @return void
     */
    public function deleted(Supplier $supplier)
    {
        //
    }

    /**
     * Handle the supplier "restored" event.
     *
     * @param  \App\Model\Supplier  $supplier
     * @return void
     */
    public function restored(Supplier $supplier)
    {
        //
    }

    /**
     * Handle the supplier "force deleted" event.
     *
     * @param  \App\Model\Supplier  $supplier
     * @return void
     */
    public function forceDeleted(Supplier $supplier)
    {
        //
    }
}
