<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\TransferStatus;
use App\Models\MaterialTransferInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialTransferInvoicePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any material transfer invoices');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return mixed
     */
    public function view(User $user, MaterialTransferInvoice $materialTransferInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view material transfer invoices') && $user->locationId == $materialTransferInvoice->locationId ) ||
                ($user->locationId == $materialTransferInvoice->receiverId && $materialTransferInvoice->status != TransferStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create material transfer invoices');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return mixed
     */
    public function update(User $user, MaterialTransferInvoice $materialTransferInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update material transfer invoices') && $user->locationId == $materialTransferInvoice->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $materialTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return mixed
     */
    public function delete(User $user, MaterialTransferInvoice $materialTransferInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete material transfer invoices') && $user->locationId == $materialTransferInvoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $materialTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return mixed
     */
    public function restore(User $user, MaterialTransferInvoice $materialTransferInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore material transfer invoices') && $user->locationId == $materialTransferInvoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data'))&&
                $materialTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return mixed
     */
    public function forceDelete(User $user, MaterialTransferInvoice $materialTransferInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete material transfer invoices') && $user->locationId == $materialTransferInvoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data'))&&
                $materialTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return mixed
     */
    public function addMaterialTransferItem(User $user, MaterialTransferInvoice $materialTransferInvoice)
    {
        return $materialTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferInvoice  $materialTransferInvoice
     * @return mixed
     */
    public function addMaterialTransferReceiveItem(User $user, MaterialTransferInvoice $materialTransferInvoice)
    {
        return true;
    }

}
