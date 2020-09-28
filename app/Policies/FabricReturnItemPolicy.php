<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\FabricReturnItem;
use App\Models\User;

class FabricReturnItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any fabric return items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\FabricReturnItem  $fabricReturnItem
     * @return mixed
     */
    public function view(User $user, FabricReturnItem $fabricReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view fabric return items') && $user->locationId == $fabricReturnItem->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create fabric return items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\FabricReturnItem  $fabricReturnItem
     * @return mixed
     */
    public function update(User $user, FabricReturnItem $fabricReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update fabric return items') && $user->locationId == $fabricReturnItem->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\FabricReturnItem  $fabricReturnItem
     * @return mixed
     */
    public function delete(User $user, FabricReturnItem $fabricReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete fabric return items') && $user->locationId == $fabricReturnItem->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\FabricReturnItem  $fabricReturnItem
     * @return mixed
     */
    public function restore(User $user, FabricReturnItem $fabricReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore fabric return items') && $user->locationId == $fabricReturnItem->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\FabricReturnItem  $fabricReturnItem
     * @return mixed
     */
    public function forceDelete(User $user, FabricReturnItem $fabricReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete fabric return items') && $user->locationId == $fabricReturnItem->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
