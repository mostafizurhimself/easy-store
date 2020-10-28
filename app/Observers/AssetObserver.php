<?php

namespace App\Observers;

use App\Models\Role;
use App\Models\Asset;
use App\Facades\Helper;
use App\Facades\Settings;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AlertQuantityNotification;

class AssetObserver
{
    /**
     * Handle the asset "creating" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function creating(Asset $asset)
    {
        $asset->quantity = $asset->openingQuantity;
    }

    /**
     * Handle the asset "saved" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function saved(Asset $asset)
    {
        if(empty($asset->code)){
            $asset->code = Helper::generateReadableId($asset->id, "AS", 5);
            $asset->save();
        }
    }

    /**
     * Handle the asset "updated" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function updated(Asset $asset)
    {
         //Notify the users
         $users = \App\Models\User::permission(['view assets', 'view any assets'])->where('location_id', $asset->locationId)->get();
         Notification::send($users, new AlertQuantityNotification(\App\Nova\Asset::uriKey(), $asset, 'Asset'));

         //Notify super admins
         if (Settings::superAdminNotification()) {
             $users = \App\Models\User::role(Role::SUPER_ADMIN)->get();
             Notification::send($users, new AlertQuantityNotification(\App\Nova\Asset::uriKey(), $asset, 'Asset'));
         }
    }

    /**
     * Handle the asset "deleted" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function deleted(Asset $asset)
    {
        //
    }

    /**
     * Handle the asset "restored" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function restored(Asset $asset)
    {
        //
    }

    /**
     * Handle the asset "force deleted" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function forceDeleted(Asset $asset)
    {
        //
    }
}
