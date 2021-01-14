<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Leave;
use App\Facades\Settings;
use App\Enums\LeaveStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeavePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any leaves');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $leave
     * @return mixed
     */
    public function view(User $user, Leave $leave)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view leaves') && $user->locationId == $leave->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create leaves');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $leave
     * @return mixed
     */
    public function update(User $user, Leave $leave)
    {
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update leaves') && $user->locationId == $leave->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $leave->status == LeaveStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $leave
     * @return mixed
     */
    public function delete(User $user, Leave $leave)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete leaves') && $user->locationId == $leave->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $leave->status == LeaveStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $leave
     * @return mixed
     */
    public function restore(User $user, Leave $leave)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore leaves') && $user->locationId == $leave->locationId ) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $leave->status == LeaveStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $leave
     * @return mixed
     */
    public function forceDelete(User $user, Leave $leave)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete leaves') && $user->locationId == $leave->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $leave->status == LeaveStatus::DRAFT();
    }
}
