<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MaterialReturnItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialReturnItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any material return items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReturnItem  $materialReturnItem
     * @return mixed
     */
    public function view(User $user, MaterialReturnItem $materialReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view material return items') && $user->locationId == $materialReturnItem->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create material return items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReturnItem  $materialReturnItem
     * @return mixed
     */
    public function update(User $user, MaterialReturnItem $materialReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update material return items') && $user->locationId == $materialReturnItem->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReturnItem  $materialReturnItem
     * @return mixed
     */
    public function delete(User $user, MaterialReturnItem $materialReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete material return items') && $user->locationId == $materialReturnItem->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReturnItem  $materialReturnItem
     * @return mixed
     */
    public function restore(User $user, MaterialReturnItem $materialReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore material return items') && $user->locationId == $materialReturnItem->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReturnItem  $materialReturnItem
     * @return mixed
     */
    public function forceDelete(User $user, MaterialReturnItem $materialReturnItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete material return items') && $user->locationId == $materialReturnItem->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
