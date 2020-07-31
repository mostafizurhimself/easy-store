<?php

namespace App\Policies;

use App\Models\Style;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StylePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any styles');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Style  $style
     * @return mixed
     */
    public function view(User $user, Style $style)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('view styles');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('create styles');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Style  $style
     * @return mixed
     */
    public function update(User $user, Style $style)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('update styles');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Style  $style
     * @return mixed
     */
    public function delete(User $user, Style $style)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('delete styles');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Style  $style
     * @return mixed
     */
    public function restore(User $user, Style $style)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('restore styles');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Style  $style
     * @return mixed
     */
    public function forceDelete(User $user, Style $style)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('force delete styles');
    }
}
