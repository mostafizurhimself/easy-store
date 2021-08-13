<?php

namespace App\Models;

use App\Facades\Settings;
use App\Enums\AddressType;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Cache;
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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['department', 'designation', 'section', 'shift'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'name', 'employeeId', 'presentAddress', 'permanentAddress', 'imageUrl',
        'departmentName', 'sectionName', 'designationName', 'shiftName'
    ];

    /**
     * Determines a morph one relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function address()
    {
        return $this->morphMany(Address::class, 'addressable');
    }


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
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    /**
     * Get the employee full name attribute
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->firstName . " " . $this->lastName;
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
     * Get billing address attribute
     *
     * @return mixed
     */
    public function getBillingAddressAttribute()
    {
        if ($this->address()->exists()) {
            return $this->address()->where('type', AddressType::PRESENT_ADDRESS())->first();
        }
    }

    /**
     * Get shipping address attribute
     *
     * @return mixed
     */
    public function getShippingAddressAttribute()
    {
        if ($this->address()->exists()) {
            return $this->address()->where('type', AddressType::PERMANENT_ADDRESS())->first();
        }
    }

    /**
     * Get employee image url
     * 
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return !empty($this->getFirstMediaUrl('employee-images')) ?
            $this->getFirstMediaUrl('employee-images') : asset('images/logo.png');
    }

    /**
     * Get department name attribute
     * 
     * @return string
     */
    public function getDepartmentNameAttribute()
    {
        return $this->department ? $this->department->name : "N/A";
    }

    /**
     * Get section name attribute
     * 
     * @return string
     */
    public function getSectionNameAttribute()
    {
        return $this->section ? $this->section->name : "N/A";
    }

    /**
     * Get designation name attribute
     * 
     * @return string
     */
    public function getDesignationNameAttribute()
    {
        return $this->designation ? $this->designation->name : "N/A";
    }

    /**
     * Get shift name attribute
     * 
     * @return string
     */
    public function getShiftNameAttribute()
    {
        return $this->shift ? $this->shift->name : "N/A";
    }


    /**
     * Get the select options of the employee
     *
     * @return array
     */
    public static function toSelectOptions()
    {
        return static::all()->map(function ($employee) {
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
        if (Settings::approvers()) {
            return static::whereIn('id', Settings::approvers())->get()->map(function ($employee) {
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
        if (Settings::gatePassApprovers()) {
            return static::whereIn('id', Settings::gatePassApprovers())->get()->map(function ($employee) {
                return ['value' => $employee->id, 'label' => "{$employee->name}({$employee->employeeId})"];
            });
        }

        return null;
    }

    /**
     * Get the filter options of employees
     *
     * @return array
     */
    public static function filterOptions()
    {
        return Cache::remember('nova-employee-filter-options', 3600, function () {
            $employees = self::setEagerLoads([])->orderBy('first_name')->get(['id', 'first_name', 'last_name']);

            return $employees->mapWithKeys(function ($employee) {
                return [$employee->first_name . " " . $employee->last_name => $employee->id];
            })->toArray();
        });
    }
}