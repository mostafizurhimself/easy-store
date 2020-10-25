<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\TransferStatus;
use App\Models\FabricTransferItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class FabricTransferItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any fabric transfer items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return mixed
     */
    public function view(User $user, FabricTransferItem $fabricTransferItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view fabric transfer items') && $user->locationId == $fabricTransferItem->invoice->locationId ) ||
                ($user->locationId == $fabricTransferItem->invoice->receiverId && $fabricTransferItem->status != TransferStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create fabric transfer items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return mixed
     */
    public function update(User $user, FabricTransferItem $fabricTransferItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update fabric transfer items') && $user->locationId == $fabricTransferItem->invoice->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $fabricTransferItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return mixed
     */
    public function delete(User $user, FabricTransferItem $fabricTransferItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete fabric transfer items') && $user->locationId == $fabricTransferItem->invoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data'))&&
                $fabricTransferItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return mixed
     */
    public function restore(User $user, FabricTransferItem $fabricTransferItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore fabric transfer items') && $user->locationId == $fabricTransferItem->invoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data'))&&
                $fabricTransferItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return mixed
     */
    public function forceDelete(User $user, FabricTransferItem $fabricTransferItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete fabric transfer items') && $user->locationId == $fabricTransferItem->invoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $fabricTransferItem->staus == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FabricTransferItem  $fabricTransferItem
     * @return mixed
     */
    public function addFabricTransferReceiveItem(User $user, FabricTransferItem $fabricTransferItem)
    {
        return ($fabricTransferItem->status == TransferStatus::CONFIRMED() || $fabricTransferItem->status == TransferStatus::PARTIAL())
                && $fabricTransferItem->invoice->receiverId == $user->locationId;
    }
}
