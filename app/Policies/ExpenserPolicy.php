<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Expenser;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpenserPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any expensers');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expenser  $expenser
     * @return mixed
     */
    public function view(User $user, Expenser $expenser)
    {
        if($user->hasRole(Role::EXPENSER)){
            return $user->id == $expenser->userId;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view expensers') && $user->locationId == $expenser->locationId ) ||
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
        if($user->hasRole(Role::EXPENSER)){
            return false;
        }
        return $user->isSuperAdmin() || $user->hasPermissionTo('create expensers');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expenser  $expenser
     * @return mixed
     */
    public function update(User $user, Expenser $expenser)
    {
        if($user->hasRole(Role::EXPENSER)){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update expensers') && $user->locationId == $expenser->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expenser  $expenser
     * @return mixed
     */
    public function delete(User $user, Expenser $expenser)
    {
        if($user->hasRole(Role::EXPENSER)){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete expensers') && $user->locationId == $expenser->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expenser  $expenser
     * @return mixed
     */
    public function restore(User $user, Expenser $expenser)
    {
        if($user->hasRole(Role::EXPENSER)){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore expensers') && $user->locationId == $expenser->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expenser  $expenser
     * @return mixed
     */
    public function forceDelete(User $user, Expenser $expenser)
    {
        if($user->hasRole(Role::EXPENSER)){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete expensers') && $user->locationId == $expenser->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
