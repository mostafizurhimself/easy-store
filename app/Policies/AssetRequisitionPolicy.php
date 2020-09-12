<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RequisitionStatus;
use App\Models\AssetRequisition;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetRequisitionPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset requisitions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetRequisition  $assetRequisition
     * @return mixed
     */
    public function view(User $user, AssetRequisition $assetRequisition)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view asset requisitions') && $user->locationId == $assetRequisition->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create asset requisitions');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetRequisition  $assetRequisition
     * @return mixed
     */
    public function update(User $user, AssetRequisition $assetRequisition)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update asset requisitions') && $user->locationId == $assetRequisition->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $assetRequisition->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetRequisition  $assetRequisition
     * @return mixed
     */
    public function delete(User $user, AssetRequisition $assetRequisition)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete asset requisitions') && $user->locationId == $assetRequisition->locationId ) ||
                $user->hasPermissionTo('delete all locations data'))&&
                $assetRequisition->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetRequisition  $assetRequisition
     * @return mixed
     */
    public function restore(User $user, AssetRequisition $assetRequisition)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore asset requisitions') && $user->locationId == $assetRequisition->locationId ) ||
                $user->hasPermissionTo('restore all locations data'))&&
                $assetRequisition->status == RequisitionStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetRequisition  $assetRequisition
     * @return mixed
     */
    public function forceDelete(User $user, AssetRequisition $assetRequisition)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete asset requisitions') && $user->locationId == $assetRequisition->locationId ) ||
                $user->hasPermissionTo('force delete all locations data'))&&
                $assetRequisition->status == RequisitionStatus::DRAFT();
    }
}
