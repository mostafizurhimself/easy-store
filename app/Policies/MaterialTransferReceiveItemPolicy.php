<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\TransferStatus;
use App\Models\MaterialTransferReceiveItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialTransferReceiveItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any material transfer receive items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferReceiveItem  $materialTransferReceiveItem
     * @return mixed
     */
    public function view(User $user, MaterialTransferReceiveItem $materialTransferReceiveItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view material transfer receive items') && $user->locationId == $materialTransferReceiveItem->invoice->receiverId ) ||
                ($user->locationId == $materialTransferReceiveItem->invoice->locationId && $materialTransferReceiveItem->status != TransferStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create material transfer receive items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferReceiveItem  $materialTransferReceiveItem
     * @return mixed
     */
    public function update(User $user, MaterialTransferReceiveItem $materialTransferReceiveItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update material transfer receive items') && $user->locationId == $materialTransferReceiveItem->invoice->receiverId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $materialTransferReceiveItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferReceiveItem  $materialTransferReceiveItem
     * @return mixed
     */
    public function delete(User $user, MaterialTransferReceiveItem $materialTransferReceiveItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete material transfer receive items') && $user->locationId == $materialTransferReceiveItem->invoice->receiverId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $materialTransferReceiveItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferReceiveItem  $materialTransferReceiveItem
     * @return mixed
     */
    public function restore(User $user, MaterialTransferReceiveItem $materialTransferReceiveItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore material transfer receive items') && $user->locationId == $materialTransferReceiveItem->invoice->receiverId ) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $materialTransferReceiveItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferReceiveItem  $materialTransferReceiveItem
     * @return mixed
     */
    public function forceDelete(User $user, MaterialTransferReceiveItem $materialTransferReceiveItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete material transfer receive items') && $user->locationId == $materialTransferReceiveItem->invoice->receiverId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $materialTransferReceiveItem->status == TransferStatus::DRAFT();
    }
}
