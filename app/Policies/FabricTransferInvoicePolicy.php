<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\TransferStatus;
use App\Models\FabricTransferInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class FabricTransferInvoicePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any fabric transfer invoices');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return mixed
     */
    public function view(User $user, FabricTransferInvoice $fabricTransferInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view fabric transfer invoices') && $user->locationId == $fabricTransferInvoice->locationId ) ||
                ($user->locationId == $fabricTransferInvoice->receiverId && $fabricTransferInvoice->status != TransferStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create fabric transfer invoices');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return mixed
     */
    public function update(User $user, FabricTransferInvoice $fabricTransferInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update fabric transfer invoices') && $user->locationId == $fabricTransferInvoice->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $fabricTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return mixed
     */
    public function delete(User $user, FabricTransferInvoice $fabricTransferInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete fabric transfer invoices') && $user->locationId == $fabricTransferInvoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $fabricTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return mixed
     */
    public function restore(User $user, FabricTransferInvoice $fabricTransferInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore fabric transfer invoices') && $user->locationId == $fabricTransferInvoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data'))&&
                $fabricTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferInvoice  $fabricTransferInvoice
     * @return mixed
     */
    public function forceDelete(User $user, FabricTransferInvoice $fabricTransferInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete fabric transfer invoices') && $user->locationId == $fabricTransferInvoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data'))&&
                $fabricTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferInvoice $fabricTransferInvoice
     * @return mixed
     */
    public function addFabricTransferItem(User $user, FabricTransferInvoice $fabricTransferInvoice)
    {
        return $fabricTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferInvoice $fabricTransferInvoice
     * @return mixed
     */
    public function addFabricTransferReceiveItem(User $user, FabricTransferInvoice $fabricTransferInvoice)
    {
        return false;
    }
}
