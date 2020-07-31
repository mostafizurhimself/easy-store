<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\PurchaseStatus;
use App\Models\FabricPurchaseItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class FabricPurchaseItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any fabric purchase items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseItem  $fabricPurchaseItem
     * @return mixed
     */
    public function view(User $user, FabricPurchaseItem $fabricPurchaseItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view fabric purchase items') && $user->locationId == $fabricPurchaseItem->locationId ) ||
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
        return ($user->isSuperAdmin() || $user->hasPermissionTo('create fabric purchase items'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseItem  $fabricPurchaseItem
     * @return mixed
     */
    public function update(User $user, FabricPurchaseItem $fabricPurchaseItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update fabric purchase items') && $user->locationId == $fabricPurchaseItem->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $fabricPurchaseItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseItem  $fabricPurchaseItem
     * @return mixed
     */
    public function delete(User $user, FabricPurchaseItem $fabricPurchaseItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete fabric purchase items') && $user->locationId == $fabricPurchaseItem->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $fabricPurchaseItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseItem  $fabricPurchaseItem
     * @return mixed
     */
    public function restore(User $user, FabricPurchaseItem $fabricPurchaseItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore fabric purchase items') && $user->locationId == $fabricPurchaseItem->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseItem  $fabricPurchaseItem
     * @return mixed
     */
    public function forceDelete(User $user, FabricPurchaseItem $fabricPurchaseItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete fabric purchase items') && $user->locationId == $fabricPurchaseItem->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $fabricPurchaseItem->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a receive item to the purchase item.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseItem  $fabricPurchaseItem
     * @return mixed
     */
    public function addFabricReceiveItem(User $user, FabricPurchaseItem $fabricPurchaseItem)
    {
        return $fabricPurchaseItem->status == PurchaseStatus::CONFIRMED() || $fabricPurchaseItem->status == PurchaseStatus::PARTIAL();
    }
}
