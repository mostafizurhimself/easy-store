<?php

namespace App\Policies;

use App\Models\MaterialCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialCategoryPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any material categories');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialCategory  $materialCategory
     * @return mixed
     */
    public function view(User $user, MaterialCategory $materialCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view material categories') && $user->locationId == $materialCategory->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create material categories');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialCategory  $materialCategory
     * @return mixed
     */
    public function update(User $user, MaterialCategory $materialCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update material categories') && $user->locationId == $materialCategory->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialCategory  $materialCategory
     * @return mixed
     */
    public function delete(User $user, MaterialCategory $materialCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete material categories') && $user->locationId == $materialCategory->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialCategory  $materialCategory
     * @return mixed
     */
    public function restore(User $user, MaterialCategory $materialCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore material categories') && $user->locationId == $materialCategory->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialCategory  $materialCategory
     * @return mixed
     */
    public function forceDelete(User $user, MaterialCategory $materialCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete material categories') && $user->locationId == $materialCategory->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
