<?php

namespace App\Observers;

use App\Models\Role;
use App\Models\Fabric;
use App\Facades\Helper;
use App\Facades\Settings;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AlertQuantityNotification;

class FabricObserver
{
    /**
     * Handle the fabric "creating" event.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return void
     */
    public function creating(Fabric $fabric)
    {
        $fabric->quantity = $fabric->openingQuantity;
    }

    /**
     * Handle the fabric "created" event.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return void
     */
    public function saved(Fabric $fabric)
    {
        if(empty($fabric->code)){
            $fabric->code = Helper::generateReadableId($fabric->id, "FB", 5);
            $fabric->save();
        }
    }

    /**
     * Handle the fabric "updated" event.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return void
     */
    public function updated(Fabric $fabric)
    {
        //Notify the users
        $users = \App\Models\User::permission(['view fabrics', 'view any fabrics'])->where('location_id', $fabric->locationId)->get();
        Notification::send($users, new AlertQuantityNotification(\App\Nova\Fabric::uriKey(), $fabric, 'Fabric'));

        //Notify super admins
        if (Settings::superAdminNotification()) {
            $users = \App\Models\User::role(Role::SUPER_ADMIN)->get();
            Notification::send($users, new AlertQuantityNotification(\App\Nova\Fabric::uriKey(), $fabric, 'Fabric'));
        }
    }

    /**
     * Handle the fabric "deleted" event.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return void
     */
    public function deleted(Fabric $fabric)
    {
        //
    }

    /**
     * Handle the fabric "restored" event.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return void
     */
    public function restored(Fabric $fabric)
    {
        //
    }

    /**
     * Handle the fabric "force deleted" event.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return void
     */
    public function forceDeleted(Fabric $fabric)
    {
        //
    }
}
