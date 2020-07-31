<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\PurchaseStatus;
use App\Models\FabricPurchaseOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class FabricPurchaseOrderPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any fabric purchase orders');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return mixed
     */
    public function view(User $user, FabricPurchaseOrder $fabricPurchaseOrder)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view fabric purchase orders') && $user->locationId == $fabricPurchaseOrder->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create fabric purchase orders');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return mixed
     */
    public function update(User $user, FabricPurchaseOrder $fabricPurchaseOrder)
    {
        return ($user->isSuperAdmin() ||
                 ($user->hasPermissionTo('update fabric purchase orders') && $user->locationId == $fabricPurchaseOrder->locationId ) ||
                $user->hasPermissionTo('update all locations data') ) &&
                $fabricPurchaseOrder->status == PurchaseStatus::DRAFT()->getValue();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return mixed
     */
    public function delete(User $user, FabricPurchaseOrder $fabricPurchaseOrder)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete fabric purchase orders') && $user->locationId == $fabricPurchaseOrder->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $fabricPurchaseOrder->status == PurchaseStatus::DRAFT()->getValue();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return mixed
     */
    public function restore(User $user, FabricPurchaseOrder $fabricPurchaseOrder)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore fabric purchase orders') && $user->locationId == $fabricPurchaseOrder->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return mixed
     */
    public function forceDelete(User $user, FabricPurchaseOrder $fabricPurchaseOrder)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete fabric purchase orders') && $user->locationId == $fabricPurchaseOrder->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $fabricPurchaseOrder->status == PurchaseStatus::DRAFT()->getValue();
    }

    /**
     * Determine whether the user can add a purchase item to the purchase.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return mixed
     */
    public function addFabricPurchaseItem(User $user, FabricPurchaseOrder $fabricPurchaseOrder)
    {
        return $fabricPurchaseOrder->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a receive item to the purchase.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricPurchaseOrder  $fabricPurchaseOrder
     * @return mixed
     */
    public function addFabricReceiveItem(User $user, FabricPurchaseOrder $fabricPurchaseOrder)
    {
        return false;
    }

}
