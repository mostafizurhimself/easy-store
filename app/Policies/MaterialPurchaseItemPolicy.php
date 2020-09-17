<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\PurchaseStatus;
use App\Models\MaterialPurchaseItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialPurchaseItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any material purchase items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseItem  $materialPurchaseItem
     * @return mixed
     */
    public function view(User $user, MaterialPurchaseItem $materialPurchaseItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view material purchase items') && $user->locationId == $materialPurchaseItem->purchaseOrder->locationId ) ||
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
        return ($user->isSuperAdmin() || $user->hasPermissionTo('create material purchase items'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseItem  $materialPurchaseItem
     * @return mixed
     */
    public function update(User $user, MaterialPurchaseItem $materialPurchaseItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update material purchase items') && $user->locationId == $materialPurchaseItem->purchaseOrder->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $materialPurchaseItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseItem  $materialPurchaseItem
     * @return mixed
     */
    public function delete(User $user, MaterialPurchaseItem $materialPurchaseItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete material purchase items') && $user->locationId == $materialPurchaseItem->purchaseOrder->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $materialPurchaseItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseItem  $materialPurchaseItem
     * @return mixed
     */
    public function restore(User $user, MaterialPurchaseItem $materialPurchaseItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore material purchase items') && $user->locationId == $materialPurchaseItem->purchaseOrder->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseItem  $materialPurchaseItem
     * @return mixed
     */
    public function forceDelete(User $user, MaterialPurchaseItem $materialPurchaseItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete material purchase items') && $user->locationId == $materialPurchaseItem->purchaseOrder->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $materialPurchaseItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a receive item to the purchase item.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseItem  $materialPurchaseItem
     * @return mixed
     */
    public function addMaterialReceiveItem(User $user, MaterialPurchaseItem $materialPurchaseItem)
    {
        return $materialPurchaseItem->status == PurchaseStatus::CONFIRMED() || $materialPurchaseItem->status == PurchaseStatus::PARTIAL();
    }
}
