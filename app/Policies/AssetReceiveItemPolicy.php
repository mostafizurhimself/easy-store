<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\PurchaseStatus;
use App\Models\AssetReceiveItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetReceiveItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset receive items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReceiveItem  $assetReceiveItem
     * @return mixed
     */
    public function view(User $user, AssetReceiveItem $assetReceiveItem)
    {
        return $user->isSuperAdmin() || ($user->hasPermissionTo('view asset receive items') && $user->locationId == $assetReceiveItem->purchaseOrder->locationId) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create asset receive items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReceiveItem  $assetReceiveItem
     * @return mixed
     */
    public function update(User $user, AssetReceiveItem $assetReceiveItem)
    {
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('update asset receive items') && $user->locationId == $assetReceiveItem->purchaseOrder->locationId) ||
            $user->hasPermissionTo('update all locations data')) &&
            $assetReceiveItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReceiveItem  $assetReceiveItem
     * @return mixed
     */
    public function delete(User $user, AssetReceiveItem $assetReceiveItem)
    {
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('delete asset receive items') && $user->locationId == $assetReceiveItem->purchaseOrder->locationId) ||
            $user->hasPermissionTo('delete all locations data')) &&
            $assetReceiveItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReceiveItem  $assetReceiveItem
     * @return mixed
     */
    public function restore(User $user, AssetReceiveItem $assetReceiveItem)
    {
        // Check the quantity is greater than the purchase item quantity or not
        // To prevent receiving item more than the purchase item
        if ($assetReceiveItem->quantity > $assetReceiveItem->purchaseItem->remainingQuantity) {
            return false;
        }
        // Check Permissions
        return $user->isSuperAdmin() || ($user->hasPermissionTo('restore asset receive items') && $user->locationId == $assetReceiveItem->purchaseOrder->locationId) ||
            $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReceiveItem  $assetReceiveItem
     * @return mixed
     */
    public function forceDelete(User $user, AssetReceiveItem $assetReceiveItem)
    {
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('force delete asset receive items') && $user->locationId == $assetReceiveItem->purchaseOrder->locationId) ||
            $user->hasPermissionTo('force delete all locations data')) &&
            $assetReceiveItem->status == PurchaseStatus::DRAFT();
    }
}
