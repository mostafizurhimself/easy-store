<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RequisitionStatus;
use App\Models\AssetRequisitionItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetRequisitionItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset requisition items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetRequisitionItem  $assetRequisitionItem
     * @return mixed
     */
    public function view(User $user, AssetRequisitionItem $assetRequisitionItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view asset requisition items') && $user->locationId == $assetRequisitionItem->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create asset requisition items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetRequisitionItem  $assetRequisitionItem
     * @return mixed
     */
    public function update(User $user, AssetRequisitionItem $assetRequisitionItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update asset requisition items') && $user->locationId == $assetRequisitionItem->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $assetRequisitionItem->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetRequisitionItem  $assetRequisitionItem
     * @return mixed
     */
    public function delete(User $user, AssetRequisitionItem $assetRequisitionItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete asset requisition items') && $user->locationId == $assetRequisitionItem->locationId ) ||
                $user->hasPermissionTo('delete all locations data'))&&
                $assetRequisitionItem->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetRequisitionItem  $assetRequisitionItem
     * @return mixed
     */
    public function restore(User $user, AssetRequisitionItem $assetRequisitionItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore asset requisition items') && $user->locationId == $assetRequisitionItem->locationId ) ||
                $user->hasPermissionTo('restore all locations data'))&&
                $assetRequisitionItem->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetRequisitionItem  $assetRequisitionItem
     * @return mixed
     */
    public function forceDelete(User $user, AssetRequisitionItem $assetRequisitionItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete asset requisition items') && $user->locationId == $assetRequisitionItem->locationId ) ||
                $user->hasPermissionTo('force delete all locations data'))&&
                $assetRequisitionItem->status == RequisitionStatus::DRAFT();
    }
}
