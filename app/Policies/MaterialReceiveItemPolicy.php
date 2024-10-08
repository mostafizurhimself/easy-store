<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\PurchaseStatus;
use App\Models\MaterialReceiveItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialReceiveItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any material receive items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReceiveItem  $materialReceiveItem
     * @return mixed
     */
    public function view(User $user, MaterialReceiveItem $materialReceiveItem)
    {
        return $user->isSuperAdmin() || ($user->hasPermissionTo('view material receive items') && $user->locationId == $materialReceiveItem->purchaseOrder->locationId) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create material receive items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReceiveItem  $materialReceiveItem
     * @return mixed
     */
    public function update(User $user, MaterialReceiveItem $materialReceiveItem)
    {
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('update material receive items') && $user->locationId == $materialReceiveItem->purchaseOrder->locationId) ||
            $user->hasPermissionTo('update all locations data')) &&
            $materialReceiveItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReceiveItem  $materialReceiveItem
     * @return mixed
     */
    public function delete(User $user, MaterialReceiveItem $materialReceiveItem)
    {
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('delete material receive items') && $user->locationId == $materialReceiveItem->purchaseOrder->locationId) ||
            $user->hasPermissionTo('delete all locations data')) &&
            $materialReceiveItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReceiveItem  $materialReceiveItem
     * @return mixed
     */
    public function restore(User $user, MaterialReceiveItem $materialReceiveItem)
    {
        // Check the quantity is greater than the purchase item quantity or not
        // To prevent receiving item more than the purchase item
        if ($materialReceiveItem->quantity > $materialReceiveItem->purchaseItem->remainingQuantity) {
            return false;
        }
        // Check Permissions
        return $user->isSuperAdmin() || ($user->hasPermissionTo('restore material receive items') && $user->locationId == $materialReceiveItem->purchaseOrder->locationId) ||
            $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReceiveItem  $materialReceiveItem
     * @return mixed
     */
    public function forceDelete(User $user, MaterialReceiveItem $materialReceiveItem)
    {
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('force delete material receive items') && $user->locationId == $materialReceiveItem->purchaseOrder->locationId) ||
            $user->hasPermissionTo('force delete all locations data')) &&
            $materialReceiveItem->status == PurchaseStatus::DRAFT();
    }
}
