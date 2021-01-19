<?php

namespace App\Policies;

use App\Models\User;
use App\Facades\Settings;
use App\Models\Attendance;
use App\Enums\AttendanceStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any attendances');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendance  $attendance
     * @return mixed
     */
    public function view(User $user, Attendance $attendance)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return $user->isSuperAdmin() ||
                ($user->hasPermissionTo('view attendances') && $user->locationId == $attendance->locationId ) ||
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('create attendances');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendance  $attendance
     * @return mixed
     */
    public function update(User $user, Attendance $attendance)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('update attendances') && $user->locationId == $attendance->locationId ) ||
                $user->hasPermissionTo('update all locations data')) &&
                $attendance->status == AttendanceStatus::DRAFT();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendance  $attendance
     * @return mixed
     */
    public function delete(User $user, Attendance $attendance)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('delete attendances') && $user->locationId == $attendance->locationId ) ||
                $user->hasPermissionTo('delete all locations data')) &&
                $attendance->status == AttendanceStatus::DRAFT();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendance  $attendance
     * @return mixed
     */
    public function restore(User $user, Attendance $attendance)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('restore attendances') && $user->locationId == $attendance->locationId ) ||
                $user->hasPermissionTo('restore all locations data')) &&
                $attendance->status == AttendanceStatus::DRAFT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendance  $attendance
     * @return mixed
     */
    public function forceDelete(User $user, Attendance $attendance)
    {
        if(!Settings::isTimesheetModuleEnabled()){
            return false;
        }
        return ($user->isSuperAdmin() ||
                ($user->hasPermissionTo('force delete attendances') && $user->locationId == $attendance->locationId ) ||
                $user->hasPermissionTo('force delete all locations data')) &&
                $attendance->status == AttendanceStatus::DRAFT();
    }

    /**
     * Determine whether the user can add a model item to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendance  $attendance
     * @return mixed
     */
    public function addModel(User $user, Attendance $attendance)
    {
        return true;
    }
}
