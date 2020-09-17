<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\DispatchStatus;
use App\Models\ServiceDispatch;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceDispatchPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any service dispatches');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceDispatch  $serviceDispatch
     * @return mixed
     */
    public function view(User $user, ServiceDispatch $serviceDispatch)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view service dispatches') && $user->locationId == $serviceDispatch->invoice->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create service dispatches');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceDispatch  $serviceDispatch
     * @return mixed
     */
    public function update(User $user, ServiceDispatch $serviceDispatch)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update service dispatches') && $user->locationId == $serviceDispatch->invoice->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $serviceDispatch->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceDispatch  $serviceDispatch
     * @return mixed
     */
    public function delete(User $user, ServiceDispatch $serviceDispatch)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete service dispatches') && $user->locationId == $serviceDispatch->invoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $serviceDispatch->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceDispatch  $serviceDispatch
     * @return mixed
     */
    public function restore(User $user, ServiceDispatch $serviceDispatch)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore service dispatches') && $user->locationId == $serviceDispatch->invoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $serviceDispatch->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceDispatch  $serviceDispatch
     * @return mixed
     */
    public function forceDelete(User $user, ServiceDispatch $serviceDispatch)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete service dispatches') && $user->locationId == $serviceDispatch->invoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $serviceDispatch->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a receive item to the dispatch.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceDispatch  $serviceDispatch
     * @return mixed
     */
    public function addServiceReceive(User $user, ServiceDispatch $serviceDispatch)
    {
        return $serviceDispatch->status == DispatchStatus::CONFIRMED() || $serviceDispatch->status == DispatchStatus::PARTIAL();
    }
}
