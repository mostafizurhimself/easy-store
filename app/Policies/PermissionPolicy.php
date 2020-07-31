<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any permissions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Spatie\Permission\Models\Permission  $permission
     * @return mixed
     */
    public function view(User $user, Permission $permission)
    {
       return $user->isSuperAdmin() || $user->hasPermissionTo('view permissions');;
    }

}
