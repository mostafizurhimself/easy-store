<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\GiftGatePass;
use App\Models\User;

class GiftGatePassPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any gift gate passes');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GiftGatePass  $giftGatePass
     * @return mixed
     */
    public function view(User $user, GiftGatePass $giftGatePass)
    {
        return $user->isSuperAdmin() ||
            ($user->hasPermissionTo('view gift gate passes') && $user->locationId == $giftGatePass->locationId) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create gift gate passes');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GiftGatePass  $giftGatePass
     * @return mixed
     */
    public function update(User $user, GiftGatePass $giftGatePass)
    {
        return $user->isSuperAdmin() ||
            ($user->hasPermissionTo('update gift gate passes') && $user->locationId == $giftGatePass->locationId) ||
            $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GiftGatePass  $giftGatePass
     * @return mixed
     */
    public function delete(User $user, GiftGatePass $giftGatePass)
    {
        return $user->isSuperAdmin() ||
            ($user->hasPermissionTo('delete gift gate passes') && $user->locationId == $giftGatePass->locationId) ||
            $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GiftGatePass  $giftGatePass
     * @return mixed
     */
    public function restore(User $user, GiftGatePass $giftGatePass)
    {
        return $user->isSuperAdmin() ||
            ($user->hasPermissionTo('restore gift gate passes') && $user->locationId == $giftGatePass->locationId) ||
            $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GiftGatePass  $giftGatePass
     * @return mixed
     */
    public function forceDelete(User $user, GiftGatePass $giftGatePass)
    {
        return $user->isSuperAdmin() ||
            ($user->hasPermissionTo('force delete gift gate passes') && $user->locationId == $giftGatePass->locationId) ||
            $user->hasPermissionTo('force delete all locations data');
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GiftGatePass  $giftGatePass
     * @return mixed
     */
    public function addModel(User $user, GiftGatePass $giftGatePass)
    {
        return true;
    }
}