<?php

namespace App\Observers;

use App\Models\Role;
use App\Facades\Settings;
use App\Models\MaterialPurchaseOrder;
use App\Notifications\NewPurchaseOrder;
use Illuminate\Support\Facades\Notification;

class MaterialPurchaseOrderObserver
{
    /**
     * Handle the material purchase order "created" event.
     *
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return void
     */
    public function created(MaterialPurchaseOrder $materialPurchaseOrder)
    {
        //Notify the users
        $users = \App\Models\User::permission(['can confirm material purchase orders'])->where('location_id', $materialPurchaseOrder->locationId)->get();
        Notification::send($users, new NewPurchaseOrder(\App\Nova\MaterialPurchaseOrder::uriKey(), $materialPurchaseOrder));

        //Notify super admins
        if(Settings::superAdminNotification())
        {
            $users = \App\Models\User::role(Role::SUPER_ADMIN)->get();
            Notification::send($users, new NewPurchaseOrder(\App\Nova\MaterialPurchaseOrder::uriKey(), $materialPurchaseOrder));
        }
    }

    /**
     * Handle the material purchase order "updating" event.
     *
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return void
     */
    public function updating(MaterialPurchaseOrder $materialPurchaseOrder)
    {
        if($materialPurchaseOrder->isDirty('location_id') || $materialPurchaseOrder->isDirty('supplier_id')){
            $materialPurchaseOrder->purchaseItems()->forceDelete();
        }
    }

    /**
     * Handle the material purchase order "deleted" event.
     *
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return void
     */
    public function deleted(MaterialPurchaseOrder $materialPurchaseOrder)
    {
        //
    }

    /**
     * Handle the material purchase order "restored" event.
     *
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return void
     */
    public function restored(MaterialPurchaseOrder $materialPurchaseOrder)
    {
        //
    }

    /**
     * Handle the material purchase order "force deleted" event.
     *
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return void
     */
    public function forceDeleted(MaterialPurchaseOrder $materialPurchaseOrder)
    {
        //
    }
}
