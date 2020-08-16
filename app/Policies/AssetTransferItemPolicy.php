<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\AssetTransferItem;
use App\Models\User;

class AssetTransferItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset transfer items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetTransferItem  $assetTransferItem
     * @return mixed
     */
    public function view(User $user, AssetTransferItem $assetTransferItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view asset transfer items') && $user->locationId == $assetTransferItem->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create asset transfer items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetTransferItem  $assetTransferItem
     * @return mixed
     */
    public function update(User $user, AssetTransferItem $assetTransferItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update asset transfer items') && $user->locationId == $assetTransferItem->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetTransferItem  $assetTransferItem
     * @return mixed
     */
    public function delete(User $user, AssetTransferItem $assetTransferItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete asset transfer items') && $user->locationId == $assetTransferItem->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetTransferItem  $assetTransferItem
     * @return mixed
     */
    public function restore(User $user, AssetTransferItem $assetTransferItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore asset transfer items') && $user->locationId == $assetTransferItem->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetTransferItem  $assetTransferItem
     * @return mixed
     */
    public function forceDelete(User $user, AssetTransferItem $assetTransferItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete asset transfer items') && $user->locationId == $assetTransferItem->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
