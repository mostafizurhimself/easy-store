<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\PurchaseStatus;
use App\Models\MaterialPurchaseOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialPurchaseOrderPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any material purchase orders');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return mixed
     */
    public function view(User $user, MaterialPurchaseOrder $materialPurchaseOrder)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view material purchase orders') && $user->locationId == $materialPurchaseOrder->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create material purchase orders');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return mixed
     */
    public function update(User $user, MaterialPurchaseOrder $materialPurchaseOrder)
    {
        return ($user->isSuperAdmin() ||
                 ($user->hasPermissionTo('update material purchase orders') && $user->locationId == $materialPurchaseOrder->locationId ) ||
                $user->hasPermissionTo('update all locations data') ) &&
                $materialPurchaseOrder->status == PurchaseStatus::DRAFT()->getValue();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return mixed
     */
    public function delete(User $user, MaterialPurchaseOrder $materialPurchaseOrder)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete material purchase orders') && $user->locationId == $materialPurchaseOrder->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $materialPurchaseOrder->status == PurchaseStatus::DRAFT()->getValue();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return mixed
     */
    public function restore(User $user, MaterialPurchaseOrder $materialPurchaseOrder)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore material purchase orders') && $user->locationId == $materialPurchaseOrder->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return mixed
     */
    public function forceDelete(User $user, MaterialPurchaseOrder $materialPurchaseOrder)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete material purchase orders') && $user->locationId == $materialPurchaseOrder->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $materialPurchaseOrder->status == PurchaseStatus::DRAFT()->getValue();
    }

    /**
     * Determine whether the user can add a purchase item to the purchase.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return mixed
     */
    public function addMaterialPurchaseItem(User $user, MaterialPurchaseOrder $materialPurchaseOrder)
    {
        return $materialPurchaseOrder->status == PurchaseStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a receive item to the purchase.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialPurchaseOrder  $materialPurchaseOrder
     * @return mixed
     */
    public function addMaterialReceiveItem(User $user, MaterialPurchaseOrder $materialPurchaseOrder)
    {
        return false;
    }

}
