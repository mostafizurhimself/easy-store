<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RequisitionStatus;
use App\Models\ProductRequisitionItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductRequisitionItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any product requisition items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductRequisitionItem  $productRequisitionItem
     * @return mixed
     */
    public function view(User $user, ProductRequisitionItem $productRequisitionItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view product requisition items') && $user->locationId == $productRequisitionItem->requisition->locationId ) ||
                ($user->locationId == $productRequisitionItem->requisition->receiverId && $productRequisitionItem->status != RequisitionStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create product requisition items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductRequisitionItem  $productRequisitionItem
     * @return mixed
     */
    public function update(User $user, ProductRequisitionItem $productRequisitionItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update product requisition items') && $user->locationId == $productRequisitionItem->requisition->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $productRequisitionItem->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductRequisitionItem  $productRequisitionItem
     * @return mixed
     */
    public function delete(User $user, ProductRequisitionItem $productRequisitionItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete product requisition items') && $user->locationId == $productRequisitionItem->requisition->locationId ) ||
                $user->hasPermissionTo('delete all locations data'))&&
                $productRequisitionItem->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductRequisitionItem  $productRequisitionItem
     * @return mixed
     */
    public function restore(User $user, ProductRequisitionItem $productRequisitionItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore product requisition items') && $user->locationId == $productRequisitionItem->requisition->locationId ) ||
                $user->hasPermissionTo('restore all locations data'))&&
                $productRequisitionItem->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductRequisitionItem  $productRequisitionItem
     * @return mixed
     */
    public function forceDelete(User $user, ProductRequisitionItem $productRequisitionItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete product requisition items') && $user->locationId == $productRequisitionItem->requisition->locationId ) ||
                $user->hasPermissionTo('force delete all locations data'))&&
                $productRequisitionItem->status == RequisitionStatus::DRAFT();
    }

}
