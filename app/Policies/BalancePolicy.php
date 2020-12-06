<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Balance;
use App\Facades\Settings;
use App\Enums\BalanceStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class BalancePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any balances');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function view(User $user, Balance $balance)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        //Check role for expenser
        if ($user->isExpenser()) {
            return $balance->expenser->userId == $user->id;
        }
        return $user->isSuperAdmin() || ($user->hasPermissionTo('view balances') && $user->locationId == $balance->locationId) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create balances');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function update(User $user, Balance $balance)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        //Check role for expenser
        if ($user->isExpenser()) {
            return $balance->expenser->userId == $user->id;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('update balances') && $user->locationId == $balance->locationId) ||
            $user->hasPermissionTo('update all locations data')) &&
            $balance->status == BalanceStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function delete(User $user, Balance $balance)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        //Check role for expenser
        if ($user->isExpenser()) {
            return $balance->expenser->userId == $user->id;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('delete balances') && $user->locationId == $balance->locationId) ||
            $user->hasPermissionTo('delete all locations data')) &&
            $balance->status == BalanceStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User     $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function restore(User $user, Balance $balance)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        //Check role for expenser
        if ($user->isExpenser()) {
            return $balance->expenser->userId == $user->id;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('restore balances') && $user->locationId == $balance->locationId) ||
            $user->hasPermissionTo('restore all locations data')) &&
            $balance->status == BalanceStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function forceDelete(User $user, Balance $balance)
    {
        // Check module is enabled or not
        if(!Settings::isExpenseModuleEnabled()){
            return false;
        }
        //Check role for expenser
        if ($user->isExpenser()) {
            return $balance->expenser->userId == $user->id;
        }
        return ($user->isSuperAdmin() || ($user->hasPermissionTo('force delete balances') && $user->locationId == $balance->locationId) ||
            $user->hasPermissionTo('force delete all locations data')) &&
            $balance->status == BalanceStatus::DRAFT();
    }

    /**
     * Determine whether the user can add an activity.
     *
     * @param  \App\Models\User     $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function addActivity(User $user, Balance $balance)
    {
        return false;
    }
}
