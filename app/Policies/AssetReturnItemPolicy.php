<?php

namespace App\Policies;

use App\Models\AssetReturnItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetReturnItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset return items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReturnItem  $assetReturnItem
     * @return mixed
     */
    public function view(User $user, AssetReturnItem $assetReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view asset return items') && $user->locationId == $assetReturnItem->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create asset return items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReturnItem  $assetReturnItem
     * @return mixed
     */
    public function update(User $user, AssetReturnItem $assetReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update asset return items') && $user->locationId == $assetReturnItem->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReturnItem  $assetReturnItem
     * @return mixed
     */
    public function delete(User $user, AssetReturnItem $assetReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete asset return items') && $user->locationId == $assetReturnItem->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReturnItem  $assetReturnItem
     * @return mixed
     */
    public function restore(User $user, AssetReturnItem $assetReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore asset return items') && $user->locationId == $assetReturnItem->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReturnItem  $assetReturnItem
     * @return mixed
     */
    public function forceDelete(User $user, AssetReturnItem $assetReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete asset return items') && $user->locationId == $assetReturnItem->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
