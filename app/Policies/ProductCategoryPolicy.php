<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\ProductCategory;
use App\Models\User;

class ProductCategoryPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any product categories');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function view(User $user, ProductCategory $productCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view product categories') && $user->locationId == $productCategory->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create product categories');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function update(User $user, ProductCategory $productCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update product categories') && $user->locationId == $productCategory->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function delete(User $user, ProductCategory $productCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete product categories') && $user->locationId == $productCategory->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function restore(User $user, ProductCategory $productCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore product categories') && $user->locationId == $productCategory->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function forceDelete(User $user, ProductCategory $productCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete product categories') && $user->locationId == $productCategory->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
