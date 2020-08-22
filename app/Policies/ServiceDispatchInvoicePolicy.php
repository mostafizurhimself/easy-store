<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\DispatchStatus;
use App\Models\ServiceDispatchInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceDispatchInvoicePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any service dispatch invoices');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceDispatchInvoice  $serviceDispatchInvoice
     * @return mixed
     */
    public function view(User $user, ServiceDispatchInvoice $serviceDispatchInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view service dispatch invoices') && $user->locationId == $serviceDispatchInvoice->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create service dispatch invoices');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceDispatchInvoice  $serviceDispatchInvoice
     * @return mixed
     */
    public function update(User $user, ServiceDispatchInvoice $serviceDispatchInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update service dispatch invoices') && $user->locationId == $serviceDispatchInvoice->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $serviceDispatchInvoice->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceDispatchInvoice  $serviceDispatchInvoice
     * @return mixed
     */
    public function delete(User $user, ServiceDispatchInvoice $serviceDispatchInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete service dispatch invoices') && $user->locationId == $serviceDispatchInvoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data'))  &&
                $serviceDispatchInvoice->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceDispatchInvoice  $serviceDispatchInvoice
     * @return mixed
     */
    public function restore(User $user, ServiceDispatchInvoice $serviceDispatchInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore service dispatch invoices') && $user->locationId == $serviceDispatchInvoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data'))  &&
                $serviceDispatchInvoice->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceDispatchInvoice  $serviceDispatchInvoice
     * @return mixed
     */
    public function forceDelete(User $user, ServiceDispatchInvoice $serviceDispatchInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete service dispatch invoices') && $user->locationId == $serviceDispatchInvoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data'))  &&
                $serviceDispatchInvoice->status == DispatchStatus::DRAFT();
    }
}
