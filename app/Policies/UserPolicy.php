<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any users');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
         //Check the user is super admin
        //Then skip it if the user is not super admin
        if($model->isSuperAdmin() && !$user->isSuperAdmin()){
            return false;
        }

        //Else check the permission
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view users') && $user->locationId == $model->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create users');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        //Check the user is super admin
        //Then skip it if the user is not super admin
        if($model->isSuperAdmin()){
            return false;
        }

        //Else check the permission
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update users') && $user->locationId == $model->locationId) ||
                $user->hasPermissionTo('update all locations data')) && $user->id != $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        //Check the user is super admin
        //Then skip it if the user is not super admin
        if($model->isSuperAdmin()){
            return false;
        }

        //Else check the permission
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete users') && $user->locationId == $model->locationId) ||
                $user->hasPermissionTo('delete all locations data')) && $user->id != $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //Check the user is super admin
        //Then skip it if the user is not super admin
        if($model->isSuperAdmin()){
            return false;
        }

        //Else check the permission
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore users') && $user->locationId == $model->locationId) ||
                $user->hasPermissionTo('restore all locations data')) && $user->id != $model->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //Check the user is super admin
        //Then skip it if the user is not super admin
        if($model->isSuperAdmin()){
            return false;
        }

        //Else check the permission
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete users') && $user->locationId == $model->locationId) ||
                $user->hasPermissionTo('force delete all locations data')) && $user->id != $model->id;
    }
}
