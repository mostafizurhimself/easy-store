<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\TransferStatus;
use App\Models\FabricTransferReceiveItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class FabricTransferReceiveItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any fabric transfer receive items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return mixed
     */
    public function view(User $user, FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        return $user->isSuperAdmin() || ($user->hasPermissionTo('view fabric transfer receive items') && $user->locationId == $fabricTransferReceiveItem->invoice->receiverId) || ($user->locationId == $fabricTransferReceiveItem->invoice->locationId && $fabricTransferReceiveItem->status != TransferStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create fabric transfer receive items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return mixed
     */
    public function update(User $user, FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('update fabric transfer receive items') && $user->locationId == $fabricTransferReceiveItem->invoice->receiverId) ||
            $user->hasPermissionTo('update all locations data')) &&
            $fabricTransferReceiveItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return mixed
     */
    public function delete(User $user, FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('delete fabric transfer receive items') && $user->locationId == $fabricTransferReceiveItem->invoice->receiverId) ||
            $user->hasPermissionTo('delete all locations data')) &&
            $fabricTransferReceiveItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return mixed
     */
    public function restore(User $user, FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        // Check the quantity is greater than the transfer item quantity or not
        // To prevent receiving item more than the transfer item
        if ($fabricTransferReceiveItem->quantity > $fabricTransferReceiveItem->transferItem->remainingQuantity) {
            return false;
        }
        // Check Permissions
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('restore fabric transfer receive items') && $user->locationId == $fabricTransferReceiveItem->invoice->receiverId) ||
            $user->hasPermissionTo('restore all locations data')) &&
            $fabricTransferReceiveItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferReceiveItem  $fabricTransferReceiveItem
     * @return mixed
     */
    public function forceDelete(User $user, FabricTransferReceiveItem $fabricTransferReceiveItem)
    {
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('force delete fabric transfer receive items') && $user->locationId == $fabricTransferReceiveItem->invoice->receiverId) ||
            $user->hasPermissionTo('force delete all locations data')) &&
            $fabricTransferReceiveItem->status == TransferStatus::DRAFT();
    }
}
