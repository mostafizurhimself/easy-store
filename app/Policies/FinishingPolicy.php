<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Finishing;
use App\Enums\FinishingStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinishingPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any finishings');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finishing  $finishing
     * @return mixed
     */
    public function view(User $user, Finishing $finishing)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view finishings') && $user->locationId == $finishing->locationId ) ||
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
        return request()->path() != "resources/".\App\Nova\Finishing::uriKey();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finishing  $finishing
     * @return mixed
     */
    public function update(User $user, Finishing $finishing)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update finishings') && $user->locationId == $finishing->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $finishing->status == FinishingStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finishing  $finishing
     * @return mixed
     */
    public function delete(User $user, Finishing $finishing)
    {
        return( $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete finishings') && $user->locationId == $finishing->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $finishing->status == FinishingStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finishing  $finishing
     * @return mixed
     */
    public function restore(User $user, Finishing $finishing)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore finishings') && $user->locationId == $finishing->locationId ) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $finishing->status == FinishingStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finishing  $finishing
     * @return mixed
     */
    public function forceDelete(User $user, Finishing $finishing)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete finishings') && $user->locationId == $finishing->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $finishing->status == FinishingStatus::DRAFT();
    }
}
