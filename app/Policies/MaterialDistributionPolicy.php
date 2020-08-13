<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\MaterialDistribution;
use App\Models\User;

class MaterialDistributionPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any material distributions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialDistribution  $materialDistribution
     * @return mixed
     */
    public function view(User $user, MaterialDistribution $materialDistribution)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view material distributions') && $user->locationId == $materialDistribution->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create material distributions');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialDistribution  $materialDistribution
     * @return mixed
     */
    public function update(User $user, MaterialDistribution $materialDistribution)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update material distributions') && $user->locationId == $materialDistribution->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialDistribution  $materialDistribution
     * @return mixed
     */
    public function delete(User $user, MaterialDistribution $materialDistribution)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete material distributions') && $user->locationId == $materialDistribution->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialDistribution  $materialDistribution
     * @return mixed
     */
    public function restore(User $user, MaterialDistribution $materialDistribution)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore material distributions') && $user->locationId == $materialDistribution->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialDistribution  $materialDistribution
     * @return mixed
     */
    public function forceDelete(User $user, MaterialDistribution $materialDistribution)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete material distributions') && $user->locationId == $materialDistribution->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
