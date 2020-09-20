<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\AssetConsume;
use App\Models\User;
use phpDocumentor\Reflection\Types\False_;

class AssetConsumePolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetConsume  $assetConsume
     * @return mixed
     */
    public function view(User $user, AssetConsume $assetConsume)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetConsume  $assetConsume
     * @return mixed
     */
    public function update(User $user, AssetConsume $assetConsume)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetConsume  $assetConsume
     * @return mixed
     */
    public function delete(User $user, AssetConsume $assetConsume)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetConsume  $assetConsume
     * @return mixed
     */
    public function restore(User $user, AssetConsume $assetConsume)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetConsume  $assetConsume
     * @return mixed
     */
    public function forceDelete(User $user, AssetConsume $assetConsume)
    {
        return false;
    }
}
