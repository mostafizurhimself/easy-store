<?php

namespace App\Policies;

use App\Models\User;
use App\Facades\Settings;
use App\Enums\GatePassStatus;
use App\Models\GoodsGatePass;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoodsGatePassPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any goods gate passes');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GoodsGatePass  $goodsGatePass
     * @return mixed
     */
    public function view(User $user, GoodsGatePass $goodsGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return $user->isSuperAdmin() || ($user->hasPermissionTo('view goods gate passes') && $user->locationId == $goodsGatePass->locationId) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create goods gate passes');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GoodsGatePass  $goodsGatePass
     * @return mixed
     */
    public function update(User $user, GoodsGatePass $goodsGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('update goods gate passes') && $user->locationId == $goodsGatePass->locationId) ||
            $user->hasPermissionTo('update all locations data')) &&
            $goodsGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GoodsGatePass  $goodsGatePass
     * @return mixed
     */
    public function delete(User $user, GoodsGatePass $goodsGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('delete goods gate passes') && $user->locationId == $goodsGatePass->locationId) ||
            $user->hasPermissionTo('delete all locations data')) &&
            $goodsGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GoodsGatePass  $goodsGatePass
     * @return mixed
     */
    public function restore(User $user, GoodsGatePass $goodsGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('restore goods gate passes') && $user->locationId == $goodsGatePass->locationId) ||
            $user->hasPermissionTo('restore all locations data')) &&
            $goodsGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GoodsGatePass  $goodsGatePass
     * @return mixed
     */
    public function forceDelete(User $user, GoodsGatePass $goodsGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('force delete goods gate passes') && $user->locationId == $goodsGatePass->locationId) ||
            $user->hasPermissionTo('force delete all locations data')) &&
            $goodsGatePass->status == GatePassStatus::DRAFT();
    }
}
