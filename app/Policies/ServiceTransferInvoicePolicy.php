<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\TransferStatus;
use App\Models\ServiceTransferInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceTransferInvoicePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any service transfer invoices');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return mixed
     */
    public function view(User $user, ServiceTransferInvoice $serviceTransferInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view service transfer invoices') && $user->locationId == $serviceTransferInvoice->locationId ) ||
                ($user->locationId == $serviceTransferInvoice->receiverId && $serviceTransferInvoice->status != TransferStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create service transfer invoices');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return mixed
     */
    public function update(User $user, ServiceTransferInvoice $serviceTransferInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update service transfer invoices') && $user->locationId == $serviceTransferInvoice->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $serviceTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return mixed
     */
    public function delete(User $user, ServiceTransferInvoice $serviceTransferInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete service transfer invoices') && $user->locationId == $serviceTransferInvoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $serviceTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return mixed
     */
    public function restore(User $user, ServiceTransferInvoice $serviceTransferInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore service transfer invoices') && $user->locationId == $serviceTransferInvoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $serviceTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return mixed
     */
    public function forceDelete(User $user, ServiceTransferInvoice $serviceTransferInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete service transfer invoices') && $user->locationId == $serviceTransferInvoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $serviceTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return mixed
     */
    public function addServiceTransferItem(User $user, ServiceTransferInvoice $serviceTransferInvoice)
    {
        return $serviceTransferInvoice->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferInvoice  $serviceTransferInvoice
     * @return mixed
     */
    public function addServiceTransferReceiveItem(User $user, ServiceTransferInvoice $serviceTransferInvoice)
    {
        return false;
    }
}
