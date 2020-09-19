<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\DispatchStatus;
use App\Models\ServiceInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceInvoicePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any service invoices');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return mixed
     */
    public function view(User $user, ServiceInvoice $serviceInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view service invoices') && $user->locationId == $serviceInvoice->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create service invoices');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return mixed
     */
    public function update(User $user, ServiceInvoice $serviceInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update service invoices') && $user->locationId == $serviceInvoice->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $serviceInvoice->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return mixed
     */
    public function delete(User $user, ServiceInvoice $serviceInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete service invoices') && $user->locationId == $serviceInvoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data'))  &&
                $serviceInvoice->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return mixed
     */
    public function restore(User $user, ServiceInvoice $serviceInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore service invoices') && $user->locationId == $serviceInvoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data'))  &&
                $serviceInvoice->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return mixed
     */
    public function forceDelete(User $user, ServiceInvoice $serviceInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete service invoices') && $user->locationId == $serviceInvoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data'))  &&
                $serviceInvoice->status == DispatchStatus::DRAFT();
    }

     /**
     * Determine whether the user can a dispatch to the invoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return mixed
     */
    public function addServiceDispatch(User $user, ServiceInvoice $serviceInvoice)
    {
        return $serviceInvoice->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a receive item to the invoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceInvoice  $serviceInvoice
     * @return mixed
     */
    public function addServiceReceive(User $user, ServiceInvoice $serviceInvoice)
    {
        return false;
    }
}
