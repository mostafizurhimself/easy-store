<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\AssetTransferOrder;
use App\Models\User;

class AssetTransferOrderPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset transfer orders');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetTransferOrder  $assetTransferOrder
     * @return mixed
     */
    public function view(User $user, AssetTransferOrder $assetTransferOrder)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view asset transfer orders') && $user->locationId == $assetTransferOrder->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create asset transfer orders');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetTransferOrder  $assetTransferOrder
     * @return mixed
     */
    public function update(User $user, AssetTransferOrder $assetTransferOrder)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update asset transfer orders') && $user->locationId == $assetTransferOrder->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetTransferOrder  $assetTransferOrder
     * @return mixed
     */
    public function delete(User $user, AssetTransferOrder $assetTransferOrder)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete asset transfer orders') && $user->locationId == $assetTransferOrder->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetTransferOrder  $assetTransferOrder
     * @return mixed
     */
    public function restore(User $user, AssetTransferOrder $assetTransferOrder)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore asset transfer orders') && $user->locationId == $assetTransferOrder->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetTransferOrder  $assetTransferOrder
     * @return mixed
     */
    public function forceDelete(User $user, AssetTransferOrder $assetTransferOrder)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete asset transfer orders') && $user->locationId == $assetTransferOrder->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
