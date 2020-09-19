<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\DistributionStatus;
use App\Models\AssetDistributionInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetDistributionInvoicePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any asset distribution invoices');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionInvoice  $assetDistributionInvoice
     * @return mixed
     */
    public function view(User $user, AssetDistributionInvoice $assetDistributionInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view asset distribution invoices') && $user->locationId == $assetDistributionInvoice->locationId ) ||
                ($user->locationId == $assetDistributionInvoice->receiverId && $assetDistributionInvoice->status != DistributionStatus::DRAFT()) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create asset distribution invoices');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionInvoice  $assetDistributionInvoice
     * @return mixed
     */
    public function update(User $user, AssetDistributionInvoice $assetDistributionInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update asset distribution invoices') && $user->locationId == $assetDistributionInvoice->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $assetDistributionInvoice->status == DistributionStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionInvoice  $assetDistributionInvoice
     * @return mixed
     */
    public function delete(User $user, AssetDistributionInvoice $assetDistributionInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete asset distribution invoices') && $user->locationId == $assetDistributionInvoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data'))&&
                $assetDistributionInvoice->status == DistributionStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionInvoice  $assetDistributionInvoice
     * @return mixed
     */
    public function restore(User $user, AssetDistributionInvoice $assetDistributionInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore asset distribution invoices') && $user->locationId == $assetDistributionInvoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data'))&&
                $assetDistributionInvoice->status == DistributionStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionInvoice  $assetDistributionInvoice
     * @return mixed
     */
    public function forceDelete(User $user, AssetDistributionInvoice $assetDistributionInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete asset distribution invoices') && $user->locationId == $assetDistributionInvoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data'))&&
                $assetDistributionInvoice->status == DistributionStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a distribution item to the distribution invoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionInvoice  $assetDistributionInvoice
     * @return mixed
     */
    public function addAssetDistributionItem(User $user, AssetDistributionInvoice $assetDistributionInvoice)
    {
        return $assetDistributionInvoice->status == DistributionStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a distribution item to the distribution invoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AssetDistributionInvoice  $assetDistributionInvoice
     * @return mixed
     */
    public function addAssetDistributionReceiveItem(User $user, AssetDistributionInvoice $assetDistributionInvoice)
    {
        return false;
    }
}
