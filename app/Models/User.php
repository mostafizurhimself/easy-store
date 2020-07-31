<?php

namespace App\Models;

use App\Traits\CamelCasing;
use App\Traits\Locationable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, HasRoles, SoftDeletes, InteractsWithMedia, LogsActivity, Locationable, CamelCasing;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Add all attributes that are not listed in $guarded for log
     *
     * @var boolean
     */
    protected static $logUnguarded = true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['prepared_permissions'];

    /**
     * @return mixed
     */
    public function getPreparedPermissionsAttribute()
    {
        return $this->permissions->pluck('name')->toArray();
    }

    /**
     * Determines if the User is a Super admin
     *
     * @return null
    */
    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Register the media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->singleFile();
    }

    /**
     * Get the user address
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
       return $this->belongsTo(Employee::class);
    }

    /**
     * Check the user is employee or not
     *
     * @return bool
     */
    public function isEmployee()
    {
        return $this->employeeId ? true : false;
    }
}
