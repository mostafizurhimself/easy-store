<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FabricCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class FabricCategoryPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any fabric categories');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricCategory  $fabricCategory
     * @return mixed
     */
    public function view(User $user, FabricCategory $fabricCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view fabric categories') && $user->locationId == $fabricCategory->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create fabric categories');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricCategory  $fabricCategory
     * @return mixed
     */
    public function update(User $user, FabricCategory $fabricCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update fabric categories') && $user->locationId == $fabricCategory->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricCategory  $fabricCategory
     * @return mixed
     */
    public function delete(User $user, FabricCategory $fabricCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete fabric categories') && $user->locationId == $fabricCategory->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricCategory  $fabricCategory
     * @return mixed
     */
    public function restore(User $user, FabricCategory $fabricCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore fabric categories') && $user->locationId == $fabricCategory->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricCategory  $fabricCategory
     * @return mixed
     */
    public function forceDelete(User $user, FabricCategory $fabricCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete fabric categories') && $user->locationId == $fabricCategory->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
