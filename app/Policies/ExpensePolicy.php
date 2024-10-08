<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Expense;
use App\Facades\Settings;
use App\Enums\ExpenseStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
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
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any expenses');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expense  $expense
     * @return mixed
     */
    public function view(User $user, Expense $expense)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        // Check role for expenser
        if ($user->isExpenser()) {
            return $expense->expenser->userId == $user->id;
        }
        return $user->isSuperAdmin() || ($user->hasPermissionTo('view expenses') && $user->locationId == $expense->locationId) ||
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
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() || $user->hasPermissionTo('create expenses');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expense  $expense
     * @return mixed
     */
    public function update(User $user, Expense $expense)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        // Check role for expenser
        if ($user->isExpenser()) {
            return $expense->expenser->userId == $user->id;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('update expenses') && $user->locationId == $expense->locationId) ||
            $user->hasPermissionTo('update all locations data')) &&
            $expense->status == ExpenseStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expense  $expense
     * @return mixed
     */
    public function delete(User $user, Expense $expense)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        // Check role for expenser
        if ($user->isExpenser()) {
            return $expense->expenser->userId == $user->id;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('delete expenses') && $user->locationId == $expense->locationId) ||
            $user->hasPermissionTo('delete all locations data')) &&
            $expense->status == ExpenseStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expense  $expense
     * @return mixed
     */
    public function restore(User $user, Expense $expense)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        // Check role for expenser
        if ($user->isExpenser()) {
            return $expense->expenser->userId == $user->id;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('restore expenses') && $user->locationId == $expense->locationId) ||
            $user->hasPermissionTo('restore all locations data')) &&
            $expense->status == ExpenseStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expense  $expense
     * @return mixed
     */
    public function forceDelete(User $user, Expense $expense)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        // Check role for expenser
        if ($user->isExpenser()) {
            return $expense->expenser->userId == $user->id;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('force delete expenses') && $user->locationId == $expense->locationId) ||
            $user->hasPermissionTo('force delete all locations data')) &&
            $expense->status == ExpenseStatus::DRAFT();
    }

    /**
     * Determine whether the user can add an activity.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expense  $expense
     * @return mixed
     */
    public function addActivity(User $user, Expense $expense)
    {
        return false;
    }
}
