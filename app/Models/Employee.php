<?php

namespace App\Models;

use App\Facades\Settings;
use App\Traits\HasReadableId;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model implements HasMedia
{
    use LogsActivity, HasReadableId, SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are not mass assignable.
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
     * Register the media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
       $this->addMediaCollection('employee-attachments')->singleFile();
    }

    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return Settings::prefix()->employee ?? 'EMP';
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdlength = 7;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'joining_date',
        'resign_date'
    ];

    /**
     * Determines one-to-one relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
       return $this->hasMany(User::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
       return $this->belongsTo(Department::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
       return $this->belongsTo(Section::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function designation()
    {
       return $this->belongsTo(Designation::class)->withTrashed();
    }

    /**
     * Get the employee full name attribute
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->firstName." ".$this->lastName;
    }

}
