<?php

namespace App\Policies;

use App\Enums\DistributionStatus;
use App\Models\User;
use App\Models\FabricDistribution;
use Illuminate\Auth\Access\HandlesAuthorization;

class FabricDistributionPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any fabric distributions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricDistribution  $fabricDistribution
     * @return mixed
     */
    public function view(User $user, FabricDistribution $fabricDistribution)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view fabric distributions') && $user->locationId == $fabricDistribution->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create fabric distributions');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricDistribution  $fabricDistribution
     * @return mixed
     */
    public function update(User $user, FabricDistribution $fabricDistribution)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update fabric distributions') && $user->locationId == $fabricDistribution->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $fabricDistribution->status == DistributionStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricDistribution  $fabricDistribution
     * @return mixed
     */
    public function delete(User $user, FabricDistribution $fabricDistribution)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete fabric distributions') && $user->locationId == $fabricDistribution->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $fabricDistribution->status == DistributionStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricDistribution  $fabricDistribution
     * @return mixed
     */
    public function restore(User $user, FabricDistribution $fabricDistribution)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore fabric distributions') && $user->locationId == $fabricDistribution->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricDistribution  $fabricDistribution
     * @return mixed
     */
    public function forceDelete(User $user, FabricDistribution $fabricDistribution)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete fabric distributions') && $user->locationId == $fabricDistribution->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $fabricDistribution->status == DistributionStatus::DRAFT();
    }
}
