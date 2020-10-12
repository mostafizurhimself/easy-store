<?php

namespace App\Policies;

use App\Enums\TransferStatus;
use App\Models\ServiceTransferItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceTransferItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any service transfer items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferItem  $serviceTransferItem
     * @return mixed
     */
    public function view(User $user, ServiceTransferItem $serviceTransferItem)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view service transfer items') && $user->locationId == $serviceTransferItem->locationId ) ||
                ($user->locationId == $serviceTransferItem->invoice->receiverId && $serviceTransferItem->status != TransferStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create service transfer items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferItem  $serviceTransferItem
     * @return mixed
     */
    public function update(User $user, ServiceTransferItem $serviceTransferItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update service transfer items') && $user->locationId == $serviceTransferItem->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $serviceTransferItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferItem  $serviceTransferItem
     * @return mixed
     */
    public function delete(User $user, ServiceTransferItem $serviceTransferItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete service transfer items') && $user->locationId == $serviceTransferItem->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $serviceTransferItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferItem  $serviceTransferItem
     * @return mixed
     */
    public function restore(User $user, ServiceTransferItem $serviceTransferItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore service transfer items') && $user->locationId == $serviceTransferItem->locationId ) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $serviceTransferItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferItem  $serviceTransferItem
     * @return mixed
     */
    public function forceDelete(User $user, ServiceTransferItem $serviceTransferItem)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete service transfer items') && $user->locationId == $serviceTransferItem->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $serviceTransferItem->status == TransferStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTransferItem  $serviceTransferItem
     * @return mixed
     */
    public function addServiceTransferReceiveItem(User $user, ServiceTransferItem $serviceTransferItem)
    {
        return ($serviceTransferItem->status == TransferStatus::CONFIRMED() || $serviceTransferItem->status == TransferStatus::PARTIAL())
        && $serviceTransferItem->invoice->receiverId == $user->locationId;
    }
}
