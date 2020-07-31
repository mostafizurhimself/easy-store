<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Asset;
use App\Models\User;

class AssetPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any assets');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Asset  $asset
     * @return mixed
     */
    public function view(User $user, Asset $asset)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view assets') && $user->locationId == $asset->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create assets');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Asset  $asset
     * @return mixed
     */
    public function update(User $user, Asset $asset)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update assets') && $user->locationId == $asset->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Asset  $asset
     * @return mixed
     */
    public function delete(User $user, Asset $asset)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete assets') && $user->locationId == $asset->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Asset  $asset
     * @return mixed
     */
    public function restore(User $user, Asset $asset)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore assets') && $user->locationId == $asset->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Asset  $asset
     * @return mixed
     */
    public function forceDelete(User $user, Asset $asset)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete assets') && $user->locationId == $asset->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
