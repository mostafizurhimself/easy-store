<?php

namespace App\Models;

use App\Facades\Settings;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model implements HasMedia
{
    use LogsActivity, SoftDeletes, InteractsWithMedia;

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
       $this->addMediaCollection('employee-attachments');
       $this->addMediaCollection('employee-images')->singleFile();
    }

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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $append = ['name', 'employeeId'];

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
    public function getNameAttribute()
    {
        return $this->firstName." ".$this->lastName;
    }


    /**
     * Get the employee id attribute
     *
     * @return string
     */
    public function getEmployeeIdAttribute()
    {
        return $this->readableId;
    }


    /**
     * Get the select options of the employee
     *
     * @return array
     */
    public static function toSelectOptions()
    {
        return static::all()->map(function($employee){
            return ['value' => $employee->id, 'label' => "{$employee->name}({$employee->employeeId})"];
        });
    }


    /**
     * Get the approvers list
     *
     * @return array
     */
    public static function approvers()
    {
        if(Settings::approvers()){
            return static::whereIn('id', Settings::approvers())->get()->map(function($employee){
                return ['value' => $employee->id, 'label' => "{$employee->name}({$employee->employeeId})"];
            });
        }

        return null;
    }

    /**
     * Get the get pass approvers list
     *
     * @return array
     */
    public static function gatePassApprovers()
    {
        if(Settings::gatePassApprovers()){
            return static::whereIn('id', Settings::gatePassApprovers())->get()->map(function($employee){
                return ['value' => $employee->id, 'label' => "{$employee->name}({$employee->employeeId})"];
            });
        }

        return null;
    }


}
