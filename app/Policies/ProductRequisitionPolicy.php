<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RequisitionStatus;
use App\Models\ProductRequisition;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductRequisitionPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any product requisitions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductRequisition  $productRequisition
     * @return mixed
     */
    public function view(User $user, ProductRequisition $productRequisition)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view product requisitions') && $user->locationId == $productRequisition->locationId ) ||
                ($user->locationId == $productRequisition->receiverId && $productRequisition->status != RequisitionStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create product requisitions');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductRequisition  $productRequisition
     * @return mixed
     */
    public function update(User $user, ProductRequisition $productRequisition)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update product requisitions') && $user->locationId == $productRequisition->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $productRequisition->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductRequisition  $productRequisition
     * @return mixed
     */
    public function delete(User $user, ProductRequisition $productRequisition)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete product requisitions') && $user->locationId == $productRequisition->locationId ) ||
                $user->hasPermissionTo('delete all locations data'))&&
                $productRequisition->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductRequisition  $productRequisition
     * @return mixed
     */
    public function restore(User $user, ProductRequisition $productRequisition)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore product requisitions') && $user->locationId == $productRequisition->locationId ) ||
                $user->hasPermissionTo('restore all locations data'))&&
                $productRequisition->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductRequisition  $productRequisition
     * @return mixed
     */
    public function forceDelete(User $user, ProductRequisition $productRequisition)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete product requisitions') && $user->locationId == $productRequisition->locationId ) ||
                $user->hasPermissionTo('force delete all locations data'))&&
                $productRequisition->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductRequisition  $productRequisition
     * @return mixed
     */
    public function addModel(User $user, ProductRequisition $productRequisition)
    {
        return true;
    }
}
