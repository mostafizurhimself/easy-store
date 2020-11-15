<?php

namespace App\Observers;

use App\Models\Role;
use App\Facades\Helper;
use App\Models\Material;
use App\Facades\Settings;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AlertQuantityNotification;

class MaterialObserver
{
    /**
     * Handle the Material "creating" event.
     *
     * @param  \App\Models\Material  $material
     * @return void
     */
    public function creating(Material $material)
    {
        $material->quantity = $material->openingQuantity;
    }

    /**
     * Handle the Material "saved" event.
     *
     * @param  \App\Models\Material  $material
     * @return void
     */
    public function saved(Material $material)
    {
        if (empty($material->code)) {
            $material->code = Helper::generateReadableId($material->id, "MR", 5);
            $material->save();
        }
    }

    /**
     * Handle the Material "updated" event.
     *
     * @param  \App\Models\Material  $material
     * @return void
     */
    public function updated(Material $material)
    {
        if ($material->alertQuantity > $material->quantity) {
            //Notify the users
            $users = \App\Models\User::permission(['view materials', 'view any materials'])->where('location_id', $material->locationId)->get();
            Notification::send($users, new AlertQuantityNotification(\App\Nova\Material::uriKey(), $material, 'Material'));

            //Notify super admins
            if (Settings::superAdminNotification()) {
                $users = \App\Models\User::role(Role::SUPER_ADMIN)->get();
                Notification::send($users, new AlertQuantityNotification(\App\Nova\Material::uriKey(), $material, 'Material'));
            }
        }
    }

    /**
     * Handle the Material "deleted" event.
     *
     * @param  \App\Models\Material  $material
     * @return void
     */
    public function deleted(Material $material)
    {
        //
    }

    /**
     * Handle the Material "restored" event.
     *
     * @param  \App\Models\Material  $material
     * @return void
     */
    public function restored(Material $material)
    {
        //
    }

    /**
     * Handle the Material "force deleted" event.
     *
     * @param  \App\Models\Material  $material
     * @return void
     */
    public function forceDeleted(Material $material)
    {
        //
    }
}
