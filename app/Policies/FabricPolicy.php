<?php

namespace App\Policies;

use App\Models\Fabric;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FabricPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any fabrics');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fabric  $fabric
     * @return mixed
     */
    public function view(User $user, Fabric $fabric)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view fabrics') && $user->locationId == $fabric->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create fabrics');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fabric  $fabric
     * @return mixed
     */
    public function update(User $user, Fabric $fabric)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update fabrics') && $user->locationId == $fabric->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fabric  $fabric
     * @return mixed
     */
    public function delete(User $user, Fabric $fabric)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete fabrics') && $user->locationId == $fabric->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fabric  $fabric
     * @return mixed
     */
    public function restore(User $user, Fabric $fabric)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore fabrics') && $user->locationId == $fabric->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fabric  $fabric
     * @return mixed
     */
    public function forceDelete(User $user, Fabric $fabric)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete fabrics') && $user->locationId == $fabric->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fabric  $fabric
     * @return mixed
     */
    public function addFabricDistribution(User $user, Fabric  $fabric)
    {
        return false;
    }
}
