<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\TransferStatus;
use App\Models\MaterialTransferItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialTransferItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any material transfer items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferItem  $materialTransferItem
     * @return mixed
     */
    public function view(User $user, MaterialTransferItem $materialTransferItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view material transfer items') && $user->locationId == $materialTransferItem->invoice->locationId ) ||
                ($user->locationId == $materialTransferItem->invoice->receiverId && $materialTransferItem->status != TransferStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create material transfer items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferItem  $materialTransferItem
     * @return mixed
     */
    public function update(User $user, MaterialTransferItem $materialTransferItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update material transfer items') && $user->locationId == $materialTransferItem->invoice->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $materialTransferItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferItem  $materialTransferItem
     * @return mixed
     */
    public function delete(User $user, MaterialTransferItem $materialTransferItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete material transfer items') && $user->locationId == $materialTransferItem->invoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data'))&&
                $materialTransferItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferItem  $materialTransferItem
     * @return mixed
     */
    public function restore(User $user, MaterialTransferItem $materialTransferItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore material transfer items') && $user->locationId == $materialTransferItem->invoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data'))&&
                $materialTransferItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferItem  $materialTransferItem
     * @return mixed
     */
    public function forceDelete(User $user, MaterialTransferItem $materialTransferItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete material transfer items') && $user->locationId == $materialTransferItem->invoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $materialTransferItem->staus == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialTransferItem  $materialTransferItem
     * @return mixed
     */
    public function addMaterialTransferReceiveItem(User $user, MaterialTransferItem $materialTransferItem)
    {
        return ($materialTransferItem->status == TransferStatus::CONFIRMED() || $materialTransferItem->status == TransferStatus::PARTIAL())
                && $materialTransferItem->invoice->receiverId == $user->locationId;
    }
}
