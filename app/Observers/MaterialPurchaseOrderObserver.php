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

        //Notify global users
        $users = \App\Models\User::permission(['can confirm asset purchase orders', 'view all locations data', 'view any locations data'])->where('location_id', "!=", $materialPurchaseOrder->locationId)->get();
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
     * Handle the material purchase order "deleting" event.
     *
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return void
     */
    public function deleting(MaterialPurchaseOrder $materialPurchaseOrder)
    {
         // Delete if force deleted
         if ($materialPurchaseOrder->isForceDeleting()) {
            // Force Delete related purchase items
            $materialPurchaseOrder->purchaseItems()->forceDelete();

            // Force Delete related receive items
            $materialPurchaseOrder->receiveItems()->forceDelete();

        }else{

            // Delete related purchase items
            $materialPurchaseOrder->purchaseItems()->delete();

            // Delete related receive items
            $materialPurchaseOrder->receiveItems()->delete();
        }
    }

    /**
     * Handle the material purchase order "restored" event.
     *
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return void
     */
    public function restored(MaterialPurchaseOrder $materialPurchaseOrder)
    {
        // Delete related purchase items
        $materialPurchaseOrder->purchaseItems()->restore();

        // Delete related receive items
        $materialPurchaseOrder->receiveItems()->restore();
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
