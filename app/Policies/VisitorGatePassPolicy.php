<?php

namespace App\Policies;

use App\Enums\GatePassStatus;
use App\Models\User;
use App\Facades\Settings;
use App\Models\VisitorGatePass;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitorGatePassPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any visitor gate passes');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VisitorGatePass  $visitorGatePass
     * @return mixed
     */
    public function view(User $user, VisitorGatePass $visitorGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view visitor gate passes') && $user->locationId == $visitorGatePass->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create visitor gate passes');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VisitorGatePass  $visitorGatePass
     * @return mixed
     */
    public function update(User $user, VisitorGatePass $visitorGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update visitor gate passes') && $user->locationId == $visitorGatePass->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $visitorGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VisitorGatePass  $visitorGatePass
     * @return mixed
     */
    public function delete(User $user, VisitorGatePass $visitorGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete visitor gate passes') && $user->locationId == $visitorGatePass->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $visitorGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VisitorGatePass  $visitorGatePass
     * @return mixed
     */
    public function restore(User $user, VisitorGatePass $visitorGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore visitor gate passes') && $user->locationId == $visitorGatePass->locationId ) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $visitorGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VisitorGatePass  $visitorGatePass
     * @return mixed
     */
    public function forceDelete(User $user, VisitorGatePass $visitorGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete visitor gate passes') && $user->locationId == $visitorGatePass->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $visitorGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VisitorGatePass  $visitorGatePass
     * @return mixed
     */
    public function addModel(User $user, VisitorGatePass $visitorGatePass)
    {
        return true;
    }
}
