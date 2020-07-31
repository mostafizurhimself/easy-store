<?php

namespace App\Policies;

use App\Models\Floor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FloorPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any floors');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Floor  $floor
     * @return mixed
     */
    public function view(User $user, Floor $floor)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view floors') &&  $user->locationId == $floor->locationId)||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create floors');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Floor  $floor
     * @return mixed
     */
    public function update(User $user, Floor $floor)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update floors') &&  $user->locationId == $floor->locationId) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Floor  $floor
     * @return mixed
     */
    public function delete(User $user, Floor $floor)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete floors') &&  $user->locationId == $floor->locationId) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Floor  $floor
     * @return mixed
     */
    public function restore(User $user, Floor $floor)
    {
        return $user->isSuperAdmin() ||
                    ($user->hasPermissionTo('restore floors') &&  $user->locationId == $floor->locationId) ||
                    $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Floor  $floor
     * @return mixed
     */
    public function forceDelete(User $user, Floor $floor)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete floors') &&  $user->locationId == $floor->locationId) ||
                $user->hasPermissionTo('force delete all locations data');
    }

}
