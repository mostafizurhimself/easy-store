<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\DistributionStatus;
use App\Models\AssetDistributionReceiveItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetDistributionReceiveItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset distribution receive items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionReceiveItem  $assetDistributionReceiveItem
     * @return mixed
     */
    public function view(User $user, AssetDistributionReceiveItem $assetDistributionReceiveItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view asset distribution receive items') && $user->locationId == $assetDistributionReceiveItem->invoice->receiverId ) ||
                ($user->locationId == $assetDistributionReceiveItem->invoice->locationId && $assetDistributionReceiveItem->status != DistributionStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create asset distribution receive items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionReceiveItem  $assetDistributionReceiveItem
     * @return mixed
     */
    public function update(User $user, AssetDistributionReceiveItem $assetDistributionReceiveItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update asset distribution receive items') && $user->locationId == $assetDistributionReceiveItem->invoice->receiverId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionReceiveItem  $assetDistributionReceiveItem
     * @return mixed
     */
    public function delete(User $user, AssetDistributionReceiveItem $assetDistributionReceiveItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete asset distribution receive items') && $user->locationId == $assetDistributionReceiveItem->invoice->receiverId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionReceiveItem  $assetDistributionReceiveItem
     * @return mixed
     */
    public function restore(User $user, AssetDistributionReceiveItem $assetDistributionReceiveItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore asset distribution receive items') && $user->locationId == $assetDistributionReceiveItem->invoice->receiverId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionReceiveItem  $assetDistributionReceiveItem
     * @return mixed
     */
    public function forceDelete(User $user, AssetDistributionReceiveItem $assetDistributionReceiveItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete asset distribution receive items') && $user->locationId == $assetDistributionReceiveItem->invoice->receiverId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
