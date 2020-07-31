<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any materials');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Material  $material
     * @return mixed
     */
    public function view(User $user, Material $material)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view materials') && $user->locationId == $material->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create materials');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Material  $material
     * @return mixed
     */
    public function update(User $user, Material $material)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update materials') && $user->locationId == $material->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Material  $material
     * @return mixed
     */
    public function delete(User $user, Material $material)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete materials') && $user->locationId == $material->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Material  $material
     * @return mixed
     */
    public function restore(User $user, Material $material)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore materials') && $user->locationId == $material->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Material  $material
     * @return mixed
     */
    public function forceDelete(User $user, Material $material)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete materials') && $user->locationId == $material->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
