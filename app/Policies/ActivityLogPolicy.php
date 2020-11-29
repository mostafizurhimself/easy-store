<?php

namespace App\Policies;

use App\Models\User;
use \Spatie\Activitylog\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityLogPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view any activity logs');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param   \Spatie\Activitylog\Models\Activity  $activity
     * @return mixed
     */
    public function view(User $user, Activity $activity)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('view activity logs');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param   \Spatie\Activitylog\Models\Activity  $activity
     * @return mixed
     */
    public function update(User $user, Activity $activity)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param   \Spatie\Activitylog\Models\Activity  $activity
     * @return mixed
     */
    public function delete(User $user, Activity $activity)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param   \Spatie\Activitylog\Models\Activity  $activity
     * @return mixed
     */
    public function restore(User $user, Activity $activity)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param   \Spatie\Activitylog\Models\Activity  $activity
     * @return mixed
     */
    public function forceDelete(User $user, Activity $activity)
    {
        return false;
    }
}
