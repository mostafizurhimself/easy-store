<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\ServiceCategory;
use App\Models\User;

class ServiceCategoryPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any service categories');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceCategory  $serviceCategory
     * @return mixed
     */
    public function view(User $user, ServiceCategory $serviceCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view service categories') && $user->locationId == $serviceCategory->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create service categories');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceCategory  $serviceCategory
     * @return mixed
     */
    public function update(User $user, ServiceCategory $serviceCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update service categories') && $user->locationId == $serviceCategory->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceCategory  $serviceCategory
     * @return mixed
     */
    public function delete(User $user, ServiceCategory $serviceCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete service categories') && $user->locationId == $serviceCategory->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceCategory  $serviceCategory
     * @return mixed
     */
    public function restore(User $user, ServiceCategory $serviceCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore service categories') && $user->locationId == $serviceCategory->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceCategory  $serviceCategory
     * @return mixed
     */
    public function forceDelete(User $user, ServiceCategory $serviceCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete service categories') && $user->locationId == $serviceCategory->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
