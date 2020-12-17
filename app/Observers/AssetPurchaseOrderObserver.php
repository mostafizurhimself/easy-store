<?php

namespace App\Observers;

use App\Models\Role;
use App\Facades\Settings;
use App\Models\AssetPurchaseOrder;
use App\Notifications\NewPurchaseOrder;
use Illuminate\Support\Facades\Notification;

class AssetPurchaseOrderObserver
{
    /**
     * Handle the asset purchase order "created" event.
     *
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function created(AssetPurchaseOrder $assetPurchaseOrder)
    {
        //Notify the users
        $users = \App\Models\User::permission(['can confirm asset purchase orders'])->where('location_id', $assetPurchaseOrder->locationId)->get();
        Notification::send($users, new NewPurchaseOrder(\App\Nova\AssetPurchaseOrder::uriKey(), $assetPurchaseOrder));

        //Notify global users
        $users = \App\Models\User::permission(['can confirm asset purchase orders', 'view all locations data', 'view any locations data'])->where('location_id', "!=", $assetPurchaseOrder->locationId)->get();
        Notification::send($users, new NewPurchaseOrder(\App\Nova\AssetPurchaseOrder::uriKey(), $assetPurchaseOrder));

        //Notify super admins
        if (Settings::superAdminNotification()) {
            $users = \App\Models\User::role(Role::SUPER_ADMIN)->get();
            Notification::send($users, new NewPurchaseOrder(\App\Nova\AssetPurchaseOrder::uriKey(), $assetPurchaseOrder));
        }
    }

    /**
     * Handle the asset purchase order "updating" event.
     *
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function updating(AssetPurchaseOrder $assetPurchaseOrder)
    {
        if ($assetPurchaseOrder->isDirty('location_id') || $assetPurchaseOrder->isDirty('supplier_id')) {
            $assetPurchaseOrder->purchaseItems()->forceDelete();
        }
    }

    /**
     * Handle the asset purchase order "deleting" event.
     *
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function deleting(AssetPurchaseOrder $assetPurchaseOrder)
    {
        // Delete if force deleted
        if ($assetPurchaseOrder->isForceDeleting()) {
            // Force Delete related purchase items
            $assetPurchaseOrder->purchaseItems()->forceDelete();

            // Force Delete related receive items
            $assetPurchaseOrder->receiveItems()->forceDelete();
        } else {

            // Delete related purchase items
            $assetPurchaseOrder->purchaseItems()->delete();

            // Delete related receive items
            $assetPurchaseOrder->receiveItems()->delete();
        }
    }

    /**
     * Handle the asset purchase order "restored" event.
     *
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function restored(AssetPurchaseOrder $assetPurchaseOrder)
    {
        // Delete related purchase items
        $assetPurchaseOrder->purchaseItems()->restore();

        // Delete related receive items
        $assetPurchaseOrder->receiveItems()->restore();
    }

    /**
     * Handle the asset purchase order "force deleted" event.
     *
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return void
     */
    public function forceDeleted(AssetPurchaseOrder $assetPurchaseOrder)
    {
        //
    }
}
