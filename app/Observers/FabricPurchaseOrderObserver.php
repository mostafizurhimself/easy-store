<?php

namespace App\Observers;

use App\Models\Role;
use App\Facades\Settings;
use App\Models\FabricPurchaseOrder;
use App\Notifications\NewPurchaseOrder;
use Illuminate\Support\Facades\Notification;

class FabricPurchaseOrderObserver
{
    /**
     * Handle the fabric purchase order "created" event.
     *
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return void
     */
    public function created(FabricPurchaseOrder $fabricPurchaseOrder)
    {
         //Notify the users
         $users = \App\Models\User::permission(['can confirm fabric purchase orders'])->where('location_id', $fabricPurchaseOrder->locationId)->get();
         Notification::send($users, new NewPurchaseOrder(\App\Nova\FabricPurchaseOrder::uriKey(), $fabricPurchaseOrder));

        //Notify global users
        $users = \App\Models\User::permission(['can confirm asset purchase orders', 'view all locations data', 'view any locations data'])->where('location_id', "!=", $fabricPurchaseOrder->locationId)->get();
        Notification::send($users, new NewPurchaseOrder(\App\Nova\FabricPurchaseOrder::uriKey(), $fabricPurchaseOrder));

        //Notify super admins
        if(Settings::superAdminNotification())
        {
            $users = \App\Models\User::role(Role::SUPER_ADMIN)->get();
            Notification::send($users, new NewPurchaseOrder(\App\Nova\FabricPurchaseOrder::uriKey(), $fabricPurchaseOrder));
        }
    }

    /**
     * Handle the fabric purchase order "updating" event.
     *
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return void
     */
    public function updating(FabricPurchaseOrder $fabricPurchaseOrder)
    {
        if($fabricPurchaseOrder->isDirty('location_id') || $fabricPurchaseOrder->isDirty('supplier_id')){
            $fabricPurchaseOrder->purchaseItems()->forceDelete();
        }
    }

    /**
     * Handle the fabric purchase order "deleted" event.
     *
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return void
     */
    public function deleted(FabricPurchaseOrder $fabricPurchaseOrder)
    {
        //
    }

    /**
     * Handle the fabric purchase order "restored" event.
     *
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return void
     */
    public function restored(FabricPurchaseOrder $fabricPurchaseOrder)
    {
        //
    }

    /**
     * Handle the fabric purchase order "force deleted" event.
     *
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return void
     */
    public function forceDeleted(FabricPurchaseOrder $fabricPurchaseOrder)
    {
        //
    }
}
