<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\PurchaseStatus;
use App\Models\AssetPurchaseItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetPurchaseItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset purchase items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseItem  $assetPurchaseItem
     * @return mixed
     */
    public function view(User $user, AssetPurchaseItem $assetPurchaseItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view asset purchase items') && $user->locationId == $assetPurchaseItem->locationId ) ||
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
        return ($user->isSuperAdmin() || $user->hasPermissionTo('create asset purchase items'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseItem  $assetPurchaseItem
     * @return mixed
     */
    public function update(User $user, AssetPurchaseItem $assetPurchaseItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update asset purchase items') && $user->locationId == $assetPurchaseItem->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $assetPurchaseItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseItem  $assetPurchaseItem
     * @return mixed
     */
    public function delete(User $user, AssetPurchaseItem $assetPurchaseItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete asset purchase items') && $user->locationId == $assetPurchaseItem->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $assetPurchaseItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseItem  $assetPurchaseItem
     * @return mixed
     */
    public function restore(User $user, AssetPurchaseItem $assetPurchaseItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore asset purchase items') && $user->locationId == $assetPurchaseItem->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseItem  $assetPurchaseItem
     * @return mixed
     */
    public function forceDelete(User $user, AssetPurchaseItem $assetPurchaseItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete asset purchase items') && $user->locationId == $assetPurchaseItem->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $assetPurchaseItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a receive item to the purchase item.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseItem  $assetPurchaseItem
     * @return mixed
     */
    public function addAssetReceiveItem(User $user, AssetPurchaseItem $assetPurchaseItem)
    {
        return $assetPurchaseItem->status == PurchaseStatus::CONFIRMED() || $assetPurchaseItem->status == PurchaseStatus::PARTIAL();
    }
}
