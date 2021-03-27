<?php

namespace App\Policies;

use App\Models\User;
use App\Facades\Settings;
use App\Enums\GatePassStatus;
use App\Models\ManualGatePass;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManualGatePassPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any manual gate passes');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManualGatePass  $manualGatePass
     * @return mixed
     */
    public function view(User $user, ManualGatePass $manualGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return $user->isSuperAdmin() || ($user->hasPermissionTo('view manual gate passes') && $user->locationId == $manualGatePass->locationId) ||
            $user->hasPermissionTo('view all locations data');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return $user->isSuperAdmin() || $user->hasPermissionTo('create manual gate passes');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManualGatePass  $manualGatePass
     * @return mixed
     */
    public function update(User $user, ManualGatePass $manualGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('update manual gate passes') && $user->locationId == $manualGatePass->locationId) ||
            $user->hasPermissionTo('update all locations data')) &&
            $manualGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManualGatePass  $manualGatePass
     * @return mixed
     */
    public function delete(User $user, ManualGatePass $manualGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('delete manual gate passes') && $user->locationId == $manualGatePass->locationId) ||
            $user->hasPermissionTo('delete all locations data')) &&
            $manualGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManualGatePass  $manualGatePass
     * @return mixed
     */
    public function restore(User $user, ManualGatePass $manualGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('restore manual gate passes') && $user->locationId == $manualGatePass->locationId) ||
            $user->hasPermissionTo('restore all locations data')) &&
            $manualGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManualGatePass  $manualGatePass
     * @return mixed
     */
    public function forceDelete(User $user, ManualGatePass $manualGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('force delete manual gate passes') && $user->locationId == $manualGatePass->locationId) ||
            $user->hasPermissionTo('force delete all locations data')) &&
            $manualGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManualGatePass  $manualGatePass
     * @return mixed
     */
    public function addModel(User $user, ManualGatePass $manualGatePass)
    {
        return true;
    }
}
