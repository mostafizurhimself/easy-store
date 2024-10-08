<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any roles');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Eminiarts\NovaPermissions\Role  $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        if($role->name == Role::SYSTEM_ADMIN && !$user->isSystemAdmin()){
            return false;
        }

        if($role->name == Role::SUPER_ADMIN && !$user->isSuperAdmin()){
            return false;
        }
        return $user->isSuperAdmin() || $user->hasPermissionTo('view roles');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('create roles');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Eminiarts\NovaPermissions\Role  $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        if($role->name == Role::SUPER_ADMIN || $role->name == Role::SYSTEM_ADMIN  || $role->name == Role::EXPENSER){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update roles') && !$user->hasRole($role->id));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Eminiarts\NovaPermissions\Role  $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        if($role->name == Role::SUPER_ADMIN || $role->name == Role::SYSTEM_ADMIN  || $role->name == Role::EXPENSER){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete roles') && !$user->hasRole($role->id));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Eminiarts\NovaPermissions\Role  $role
     * @return mixed
     */
    public function restore(User $user, Role $role)
    {
        if($role->name == Role::SUPER_ADMIN || $role->name == Role::SYSTEM_ADMIN  || $role->name == Role::EXPENSER){
            return false;
        }

        return $user->isSuperAdmin() ||
            ($user->hasPermissionTo('restore roles') && !$user->hasRole($role->id));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Eminiarts\NovaPermissions\Role  $role
     * @return mixed
     */
    public function forceDelete(User $user, Role $role)
    {
        if($role->name == Role::SUPER_ADMIN && $role->name == Role::SYSTEM_ADMIN  || $role->name == Role::EXPENSER){
            return false;
        }

        return $user->isSuperAdmin() ||
            ($user->hasPermissionTo('force delete roles') && !$user->hasRole($role->id));
    }

    /**
     * Determine whether the user can add a receive item to the purchase.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return mixed
     */
    public function attachAnyUser(User $user, Role $role)
    {
        return $user->hasPermissionTo('can attach users') || $user->isSuperAdmin();
    }
}
