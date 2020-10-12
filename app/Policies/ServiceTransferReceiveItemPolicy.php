<?php

namespace App\Policies;

use App\Enums\TransferStatus;
use App\Models\ServiceTransferReceiveItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceTransferReceiveItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any service transfer receive items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return mixed
     */
    public function view(User $user, ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view service transfer receive items') && $user->locationId == $serviceTransferReceiveItem->invoice->receiverId ) ||
                ($user->locationId == $serviceTransferReceiveItem->invoice->locationId && $serviceTransferReceiveItem->status != TransferStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create service transfer receive items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return mixed
     */
    public function update(User $user, ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update service transfer receive items') && $user->locationId == $serviceTransferReceiveItem->invoice->receiverId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $serviceTransferReceiveItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return mixed
     */
    public function delete(User $user, ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete service transfer receive items') && $user->locationId == $serviceTransferReceiveItem->invoice->receiverId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $serviceTransferReceiveItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return mixed
     */
    public function restore(User $user, ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore service transfer receive items') && $user->locationId == $serviceTransferReceiveItem->invoice->receiverId ) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $serviceTransferReceiveItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferReceiveItem  $serviceTransferReceiveItem
     * @return mixed
     */
    public function forceDelete(User $user, ServiceTransferReceiveItem $serviceTransferReceiveItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete service transfer receive items') && $user->locationId == $serviceTransferReceiveItem->invoice->receiverId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $serviceTransferReceiveItem->status == TransferStatus::DRAFT();
    }
}
