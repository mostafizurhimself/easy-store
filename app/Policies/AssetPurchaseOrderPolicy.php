<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\PurchaseStatus;
use App\Models\AssetPurchaseOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetPurchaseOrderPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset purchase orders');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return mixed
     */
    public function view(User $user, AssetPurchaseOrder $assetPurchaseOrder)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view asset purchase orders') && $user->locationId == $assetPurchaseOrder->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create asset purchase orders');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return mixed
     */
    public function update(User $user, AssetPurchaseOrder $assetPurchaseOrder)
    {
        return ($user->isSuperAdmin() ||
                 ($user->hasPermissionTo('update asset purchase orders') && $user->locationId == $assetPurchaseOrder->locationId ) ||
                $user->hasPermissionTo('update all locations data') ) &&
                $assetPurchaseOrder->status == PurchaseStatus::DRAFT()->getValue();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return mixed
     */
    public function delete(User $user, AssetPurchaseOrder $assetPurchaseOrder)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete asset purchase orders') && $user->locationId == $assetPurchaseOrder->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $assetPurchaseOrder->status == PurchaseStatus::DRAFT()->getValue();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return mixed
     */
    public function restore(User $user, AssetPurchaseOrder $assetPurchaseOrder)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore asset purchase orders') && $user->locationId == $assetPurchaseOrder->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return mixed
     */
    public function forceDelete(User $user, AssetPurchaseOrder $assetPurchaseOrder)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete asset purchase orders') && $user->locationId == $assetPurchaseOrder->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $assetPurchaseOrder->status == PurchaseStatus::DRAFT()->getValue();
    }

    /**
     * Determine whether the user can add a purchase item to the purchase.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return mixed
     */
    public function addAssetPurchaseItem(User $user, AssetPurchaseOrder $assetPurchaseOrder)
    {
        return $assetPurchaseOrder->status == PurchaseStatus::DRAFT() && $assetPurchaseOrder->purchaseItems()->count() <= \App\Facades\Settings::maxInvoiceItem();
    }

    /**
     * Determine whether the user can add a receive item to the purchase.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetPurchaseOrder  $assetPurchaseOrder
     * @return mixed
     */
    public function addAssetReceiveItem(User $user, AssetPurchaseOrder $assetPurchaseOrder)
    {
        return false;
    }

}
