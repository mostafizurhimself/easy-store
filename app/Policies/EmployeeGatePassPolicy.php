<?php

namespace App\Policies;

use App\Enums\GatePassStatus;
use App\Models\User;
use App\Facades\Settings;
use App\Models\EmployeeGatePass;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeGatePassPolicy
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
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any employee gate passes');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EmployeeGatePass  $employeeGatePass
     * @return mixed
     */
    public function view(User $user, EmployeeGatePass $employeeGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view employee gate passes') && $user->locationId == $employeeGatePass->locationId ) ||
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
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return $user->isSuperAdmin() || $user->hasPermissionTo('create employee gate passes');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EmployeeGatePass  $employeeGatePass
     * @return mixed
     */
    public function update(User $user, EmployeeGatePass $employeeGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update employee gate passes') && $user->locationId == $employeeGatePass->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $employeeGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EmployeeGatePass  $employeeGatePass
     * @return mixed
     */
    public function delete(User $user, EmployeeGatePass $employeeGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete employee gate passes') && $user->locationId == $employeeGatePass->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $employeeGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EmployeeGatePass  $employeeGatePass
     * @return mixed
     */
    public function restore(User $user, EmployeeGatePass $employeeGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore employee gate passes') && $user->locationId == $employeeGatePass->locationId ) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $employeeGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EmployeeGatePass  $employeeGatePass
     * @return mixed
     */
    public function forceDelete(User $user, EmployeeGatePass $employeeGatePass)
    {
        // Check module is enabled or not
        if (!Settings::isGatePassModuleEnabled()) {
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete employee gate passes') && $user->locationId == $employeeGatePass->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $employeeGatePass->status == GatePassStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EmployeeGatePass  $employeeGatePass
     * @return mixed
     */
    public function addModel(User $user, EmployeeGatePass $employeeGatePass)
    {
        return true;
    }
}
