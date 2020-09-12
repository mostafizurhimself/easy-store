<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\SubSection;
use App\Models\User;

class SubSectionPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any sub sections');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSection  $subSection
     * @return mixed
     */
    public function view(User $user, SubSection $subSection)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view sub sections') && $user->locationId == $subSection->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create sub sections');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSection  $subSection
     * @return mixed
     */
    public function update(User $user, SubSection $subSection)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update sub sections') && $user->locationId == $subSection->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSection  $subSection
     * @return mixed
     */
    public function delete(User $user, SubSection $subSection)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete sub sections') && $user->locationId == $subSection->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSection  $subSection
     * @return mixed
     */
    public function restore(User $user, SubSection $subSection)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore sub sections') && $user->locationId == $subSection->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSection  $subSection
     * @return mixed
     */
    public function forceDelete(User $user, SubSection $subSection)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete sub sections') && $user->locationId == $subSection->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
