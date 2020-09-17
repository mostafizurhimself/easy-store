<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\DispatchStatus;
use App\Models\ServiceReceive;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceReceivePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any service receives');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceReceive  $serviceReceive
     * @return mixed
     */
    public function view(User $user, ServiceReceive $serviceReceive)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view service receives') && $user->locationId == $serviceReceive->invoice->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create service receives');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceReceive  $serviceReceive
     * @return mixed
     */
    public function update(User $user, ServiceReceive $serviceReceive)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update service receives') && $user->locationId == $serviceReceive->invoice->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $serviceReceive->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceReceive  $serviceReceive
     * @return mixed
     */
    public function delete(User $user, ServiceReceive $serviceReceive)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete service receives') && $user->locationId == $serviceReceive->invoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $serviceReceive->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceReceive  $serviceReceive
     * @return mixed
     */
    public function restore(User $user, ServiceReceive $serviceReceive)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore service receives') && $user->locationId == $serviceReceive->invoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $serviceReceive->status == DispatchStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceReceive  $serviceReceive
     * @return mixed
     */
    public function forceDelete(User $user, ServiceReceive $serviceReceive)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete service receives') && $user->locationId == $serviceReceive->invoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $serviceReceive->status == DispatchStatus::DRAFT();
    }
}
