<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Provider;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProviderPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any providers');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Provider  $provider
     * @return mixed
     */
    public function view(User $user, Provider $provider)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('view providers');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('create providers');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Provider  $provider
     * @return mixed
     */
    public function update(User $user, Provider $provider)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('update providers');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Provider  $provider
     * @return mixed
     */
    public function delete(User $user, Provider $provider)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('delete providers');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Provider  $provider
     * @return mixed
     */
    public function restore(User $user, Provider $provider)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('restore providers');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Provider  $provider
     * @return mixed
     */
    public function forceDelete(User $user, Provider $provider)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('force delete providers');
    }
}
