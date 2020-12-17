<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\PurchaseStatus;
use App\Models\FabricReceiveItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class FabricReceiveItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any fabric receive items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricReceiveItem  $fabricReceiveItem
     * @return mixed
     */
    public function view(User $user, FabricReceiveItem $fabricReceiveItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view fabric receive items') && $user->locationId == $fabricReceiveItem->purchaseOrder->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create fabric receive items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricReceiveItem  $fabricReceiveItem
     * @return mixed
     */
    public function update(User $user, FabricReceiveItem $fabricReceiveItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update fabric receive items') && $user->locationId == $fabricReceiveItem->purchaseOrder->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $fabricReceiveItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricReceiveItem  $fabricReceiveItem
     * @return mixed
     */
    public function delete(User $user, FabricReceiveItem $fabricReceiveItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete fabric receive items') && $user->locationId == $fabricReceiveItem->purchaseOrder->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $fabricReceiveItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricReceiveItem  $fabricReceiveItem
     * @return mixed
     */
    public function restore(User $user, FabricReceiveItem $fabricReceiveItem)
    {
        // Check the quantity is greater than the purchase item quantity or not
        // To prevent receiving item more than the purchase item
        if($fabricReceiveItem->quantity > $fabricReceiveItem->purchaseItem->remainingQuantity){
            return false;
        }
        // Check permissions
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore fabric receive items') && $user->locationId == $fabricReceiveItem->purchaseOrder->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricReceiveItem  $fabricReceiveItem
     * @return mixed
     */
    public function forceDelete(User $user, FabricReceiveItem $fabricReceiveItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete fabric receive items') && $user->locationId == $fabricReceiveItem->purchaseOrder->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $fabricReceiveItem->status == PurchaseStatus::DRAFT();
    }
}
