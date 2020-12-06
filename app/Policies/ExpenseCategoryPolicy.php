<?php

namespace App\Policies;

use App\Models\User;
use App\Facades\Settings;
use App\Models\ExpenseCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpenseCategoryPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any expense categories');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return mixed
     */
    public function view(User $user, ExpenseCategory $expenseCategory)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view expense categories') && $user->locationId == $expenseCategory->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create expense categories');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return mixed
     */
    public function update(User $user, ExpenseCategory $expenseCategory)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update expense categories') && $user->locationId == $expenseCategory->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return mixed
     */
    public function delete(User $user, ExpenseCategory $expenseCategory)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete expense categories') && $user->locationId == $expenseCategory->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return mixed
     */
    public function restore(User $user, ExpenseCategory $expenseCategory)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore expense categories') && $user->locationId == $expenseCategory->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return mixed
     */
    public function forceDelete(User $user, ExpenseCategory $expenseCategory)
    {
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete expense categories') && $user->locationId == $expenseCategory->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }
}
