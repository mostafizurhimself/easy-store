<?php

namespace App\Policies;

use App\Models\Section;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SectionPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any sections');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Section  $section
     * @return mixed
     */
    public function view(User $user, Section $section)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view sections') && $user->locationId == $section->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create sections');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Section  $section
     * @return mixed
     */
    public function update(User $user, Section $section)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update sections') && $user->locationId == $section->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Section  $section
     * @return mixed
     */
    public function delete(User $user, Section $section)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete sections') && $user->locationId == $section->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Section  $section
     * @return mixed
     */
    public function restore(User $user, Section $section)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore sections') && $user->locationId == $section->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Section  $section
     * @return mixed
     */
    public function forceDelete(User $user, Section $section)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete sections') && $user->locationId == $section->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
