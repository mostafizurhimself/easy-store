<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\ReturnStatus;
use App\Models\MaterialReturnInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialReturnInvoicePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any material return invoices');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReturnInvoice  $materialReturnInvoice
     * @return mixed
     */
    public function view(User $user, MaterialReturnInvoice $materialReturnInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view material return invoices') && $user->locationId == $materialReturnInvoice->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create material return invoices');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReturnInvoice  $materialReturnInvoice
     * @return mixed
     */
    public function update(User $user, MaterialReturnInvoice $materialReturnInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update material return invoices') && $user->locationId == $materialReturnInvoice->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReturnInvoice  $materialReturnInvoice
     * @return mixed
     */
    public function delete(User $user, MaterialReturnInvoice $materialReturnInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete material return invoices') && $user->locationId == $materialReturnInvoice->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReturnInvoice  $materialReturnInvoice
     * @return mixed
     */
    public function restore(User $user, MaterialReturnInvoice $materialReturnInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore material return invoices') && $user->locationId == $materialReturnInvoice->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReturnInvoice  $materialReturnInvoice
     * @return mixed
     */
    public function forceDelete(User $user, MaterialReturnInvoice $materialReturnInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete material return invoices') && $user->locationId == $materialReturnInvoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaterialReturnInvoice  $materialReturnInvoice
     * @return mixed
     */
    public function addMaterialReturnItem(User $user, MaterialReturnInvoice $materialReturnInvoice)
    {
        return $materialReturnInvoice->status == ReturnStatus::DRAFT();
    }
}
