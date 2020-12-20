<?php

namespace App\Policies;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShiftPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any shifts');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shift  $shift
     * @return mixed
     */
    public function view(User $user, Shift $shift)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view shifts') && $user->locationId == $shift->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create shifts');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shift  $shift
     * @return mixed
     */
    public function update(User $user, Shift $shift)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update shifts') && $user->locationId == $shift->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shift  $shift
     * @return mixed
     */
    public function delete(User $user, Shift $shift)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete shifts') && $user->locationId == $shift->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shift  $shift
     * @return mixed
     */
    public function restore(User $user, Shift $shift)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore shifts') && $user->locationId == $shift->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shift  $shift
     * @return mixed
     */
    public function forceDelete(User $user, Shift $shift)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete shifts') && $user->locationId == $shift->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shift  $shift
     * @return mixed
     */
    public function addModel(User $user, Shift $shift)
    {
        return true;
    }
}
