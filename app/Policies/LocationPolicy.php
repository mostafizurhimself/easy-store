<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any locations');
        // return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Location  $location
     * @return mixed
     */
    public function view(User $user, Location $location)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('view locations');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('create locations');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Location  $location
     * @return mixed
     */
    public function update(User $user, Location $location)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('update locations');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Location  $location
     * @return mixed
     */
    public function delete(User $user, Location $location)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('delete locations');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Location  $location
     * @return mixed
     */
    public function restore(User $user, Location $location)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('restore locations');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Location  $location
     * @return mixed
     */
    public function forceDelete(User $user, Location $location)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('force delete locations');
    }
}
