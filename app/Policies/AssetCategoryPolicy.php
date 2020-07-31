<?php

namespace App\Policies;

use App\Models\AssetCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetCategoryPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset categories');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetCategory  $assetCategory
     * @return mixed
     */
    public function view(User $user, AssetCategory $assetCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view asset categories') && $user->locationId == $assetCategory->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create asset categories');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetCategory  $assetCategory
     * @return mixed
     */
    public function update(User $user, AssetCategory $assetCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update asset categories') && $user->locationId == $assetCategory->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetCategory  $assetCategory
     * @return mixed
     */
    public function delete(User $user, AssetCategory $assetCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete asset categories') && $user->locationId == $assetCategory->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetCategory  $assetCategory
     * @return mixed
     */
    public function restore(User $user, AssetCategory $assetCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore asset categories') && $user->locationId == $assetCategory->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetCategory  $assetCategory
     * @return mixed
     */
    public function forceDelete(User $user, AssetCategory $assetCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete asset categories') && $user->locationId == $assetCategory->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
