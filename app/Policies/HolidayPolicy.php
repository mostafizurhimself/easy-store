<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Holiday;
use App\Facades\Settings;
use Illuminate\Auth\Access\HandlesAuthorization;

class HolidayPolicy
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
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any holidays');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Holiday  $holiday
     * @return mixed
     */
    public function view(User $user, Holiday $holiday)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view holidays') && $user->locationId == $holiday->locationId ) ||
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
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() || $user->hasPermissionTo('create holidays');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Holiday  $holiday
     * @return mixed
     */
    public function update(User $user, Holiday $holiday)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('update holidays') && $user->locationId == $holiday->locationId ) ||
                $user->hasPermissionTo('update all locations data');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Holiday  $holiday
     * @return mixed
     */
    public function delete(User $user, Holiday $holiday)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete holidays') && $user->locationId == $holiday->locationId ) ||
                $user->hasPermissionTo('delete all locations data');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Holiday  $holiday
     * @return mixed
     */
    public function restore(User $user, Holiday $holiday)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore holidays') && $user->locationId == $holiday->locationId ) ||
                $user->hasPermissionTo('restore all locations data');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Holiday  $holiday
     * @return mixed
     */
    public function forceDelete(User $user, Holiday $holiday)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete holidays') && $user->locationId == $holiday->locationId ) ||
                $user->hasPermissionTo('force delete all locations data');
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Holiday  $holiday
     * @return mixed
     */
    public function addModel(User $user, Holiday $holiday)
    {
        return true;
    }
}
