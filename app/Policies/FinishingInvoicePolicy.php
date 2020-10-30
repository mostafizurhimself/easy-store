<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\FinishingStatus;
use App\Models\FinishingInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinishingInvoicePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any finishing invoices');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FinishingInvoice  $finishingInvoice
     * @return mixed
     */
    public function view(User $user, FinishingInvoice $finishingInvoice)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view finishing invoices') && $user->locationId == $finishingInvoice->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create finishing invoices');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FinishingInvoice  $finishingInvoice
     * @return mixed
     */
    public function update(User $user, FinishingInvoice $finishingInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update finishing invoices') && $user->locationId == $finishingInvoice->locationId) ||
                $user->hasPermissionTo('update all locations data')) &&
                $finishingInvoice->status == FinishingStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FinishingInvoice  $finishingInvoice
     * @return mixed
     */
    public function delete(User $user, FinishingInvoice $finishingInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete finishing invoices') && $user->locationId == $finishingInvoice->locationId) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $finishingInvoice->status == FinishingStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FinishingInvoice  $finishingInvoice
     * @return mixed
     */
    public function restore(User $user, FinishingInvoice $finishingInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore finishing invoices') && $user->locationId == $finishingInvoice->locationId) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $finishingInvoice->status == FinishingStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FinishingInvoice  $finishingInvoice
     * @return mixed
     */
    public function forceDelete(User $user, FinishingInvoice $finishingInvoice)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete finishing invoices') && $user->locationId == $finishingInvoice->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $finishingInvoice->status == FinishingStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a finishing to the invoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FinishingInvoice  $finishingInvoice
     * @return mixed
     */
    public function addFinishing(User $user, FinishingInvoice $finishingInvoice)
    {
        return  $finishingInvoice->status == FinishingStatus::DRAFT() && $finishingInvoice->finishings()->count() <= \App\Facades\Settings::maxInvoiceItem() ;
    }

}
