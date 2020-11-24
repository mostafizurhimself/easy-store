<?php

namespace App\Models;

use App\Traits\Authorize;
use App\Traits\CamelCasing;
use App\Traits\Locationable;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, HasRoles, SoftDeletes, InteractsWithMedia, LogsActivity, Locationable, CamelCasing, Authorize;

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
     * Determines one-to-one relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function expenser()
    {
        return $this->hasOne(Expenser::class);
    }

    /**
     * Determines if the User is a Super admin
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determines if the User is not a Super admin
     *
     * @return boolean
     */
    public function isNotSuperAdmin()
    {
        return !$this->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determines if the User is a System admin
     *
     * @return boolean
     */
    public function isSystemAdmin()
    {
        return $this->hasRole(Role::SYSTEM_ADMIN);
    }

    /**
     * Determines if the User is an expenser
     *
     * @return boolean
     */
    public function isExpenser()
    {
        return $this->hasRole(Role::EXPENSER);
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
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consumes()
    {
        return $this->hasMany(AssetConsume::class, 'consumer');
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

    /**
     * Get the filter options of the model
     *
     * @return array
     */
    public static function filterOptions()
    {
        // Cache::forget('nova-user-filter-options');
        return Cache::remember('nova-user-filter-options', 3600, function () {
            return self::pluck('id', 'name')->toArray();
        });
    }
}
