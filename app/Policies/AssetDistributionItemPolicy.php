<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\DistributionStatus;
use App\Models\AssetDistributionItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetDistributionItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset distribution items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionItem  $assetDistributionItem
     * @return mixed
     */
    public function view(User $user, AssetDistributionItem $assetDistributionItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view asset distribution items') && $user->locationId == $assetDistributionItem->invoice->locationId ) ||
                ($user->locationId == $assetDistributionItem->invoice->receiverId && $assetDistributionItem->status != DistributionStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create asset distribution items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionItem  $assetDistributionItem
     * @return mixed
     */
    public function update(User $user, AssetDistributionItem $assetDistributionItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update asset distribution items') && $user->locationId == $assetDistributionItem->invoice->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $assetDistributionItem->status == DistributionStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionItem  $assetDistributionItem
     * @return mixed
     */
    public function delete(User $user, AssetDistributionItem $assetDistributionItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete asset distribution items') && $user->locationId == $assetDistributionItem->invoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data'))&&
                $assetDistributionItem->status == DistributionStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionItem  $assetDistributionItem
     * @return mixed
     */
    public function restore(User $user, AssetDistributionItem $assetDistributionItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore asset distribution items') && $user->locationId == $assetDistributionItem->invoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data'))&&
                $assetDistributionItem->status == DistributionStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionItem  $assetDistributionItem
     * @return mixed
     */
    public function forceDelete(User $user, AssetDistributionItem $assetDistributionItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete asset distribution items') && $user->locationId == $assetDistributionItem->invoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $assetDistributionItem->staus == DistributionStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a receive item to the distribution item.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionItem  $assetDistributionItem
     * @return mixed
     */
    public function addAssetDistributionReceiveItem(User $user, AssetDistributionItem $assetDistributionItem)
    {
        return ($assetDistributionItem->status == DistributionStatus::CONFIRMED() || $assetDistributionItem->status == DistributionStatus::PARTIAL())
                && $assetDistributionItem->invoice->receiverId == $user->locationId;
    }

}
