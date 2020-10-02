<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\ReturnStatus;
use App\Models\AssetReturnInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetReturnInvoicePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset return invoices');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReturnInvoice  $assetReturnInvoice
     * @return mixed
     */
    public function view(User $user, AssetReturnInvoice $assetReturnInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view asset return invoices') && $user->locationId == $assetReturnInvoice->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create asset return invoices');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReturnInvoice  $assetReturnInvoice
     * @return mixed
     */
    public function update(User $user, AssetReturnInvoice $assetReturnInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update asset return invoices') && $user->locationId == $assetReturnInvoice->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $assetReturnInvoice->status == ReturnStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReturnInvoice  $assetReturnInvoice
     * @return mixed
     */
    public function delete(User $user, AssetReturnInvoice $assetReturnInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete asset return invoices') && $user->locationId == $assetReturnInvoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $assetReturnInvoice->status == ReturnStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReturnInvoice  $assetReturnInvoice
     * @return mixed
     */
    public function restore(User $user, AssetReturnInvoice $assetReturnInvoice)
    {
        return( $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore asset return invoices') && $user->locationId == $assetReturnInvoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $assetReturnInvoice->status == ReturnStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReturnInvoice  $assetReturnInvoice
     * @return mixed
     */
    public function forceDelete(User $user, AssetReturnInvoice $assetReturnInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete asset return invoices') && $user->locationId == $assetReturnInvoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $assetReturnInvoice->status == ReturnStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetReturnInvoice  $assetReturnInvoice
     * @return mixed
     */
    public function addAssetReturnItem(User $user, AssetReturnInvoice $assetReturnInvoice)
    {
        return $assetReturnInvoice->status == ReturnStatus::DRAFT();
    }
}
