<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\ProductOutput;
use App\Models\User;

class ProductOutputPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any product outputs');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductOutput  $productOutput
     * @return mixed
     */
    public function view(User $user, ProductOutput $productOutput)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view product outputs') && $user->locationId == $productOutput->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create product outputs');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductOutput  $productOutput
     * @return mixed
     */
    public function update(User $user, ProductOutput $productOutput)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update product outputs') && $user->locationId == $productOutput->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductOutput  $productOutput
     * @return mixed
     */
    public function delete(User $user, ProductOutput $productOutput)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete product outputs') && $user->locationId == $productOutput->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductOutput  $productOutput
     * @return mixed
     */
    public function restore(User $user, ProductOutput $productOutput)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore product outputs') && $user->locationId == $productOutput->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductOutput  $productOutput
     * @return mixed
     */
    public function forceDelete(User $user, ProductOutput $productOutput)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete product outputs') && $user->locationId == $productOutput->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
