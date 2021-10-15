<?php

namespace App\Models;

use Carbon\Carbon;
use App\Facades\Settings;
use App\Enums\AddressType;
use App\Enums\LeaveStatus;
use App\Facades\Timesheet;
use App\Enums\ConfirmStatus;
use App\Enums\EmployeeStatus;
use App\Enums\GatePassStatus;
use App\Scopes\ResignedScope;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    // /**
    //  * The "booted" method of the model.
    //  *
    //  * @return void
    //  */
    // protected static function booted()
    // {
    //     static::addGlobalScope(new ResignedScope);
    // }

    /**
     * Scope a query to only without resigned employees.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutResigned($query)
    {
        return $query->where('status', "!=", EmployeeStatus::RESIGNED());
    }


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
        'dob',
        'joining_date',
        'resign_date',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['department', 'designation', 'section', 'shift', 'location', 'media', 'address'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'name', 'employeeId', 'presentAddress', 'permanentAddress', 'imageUrl',
        'departmentName', 'sectionName', 'designationName', 'shiftName', 'nameWithId'
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
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaveDays()
    {
        return $this->hasMany(LeaveDays::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gatePasses()
    {
        return $this->hasMany(EmployeeGatePass::class);
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
     * Get the employee full name attribute
     *
     * @return string
     */
    public function getNameWithIdAttribute()
    {
        return "{$this->firstName} {$this->lastName} ($this->readableId)";
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
    public function getPresentAddressAttribute()
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
    public function getPermanentAddressAttribute()
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
            $this->getFirstMediaUrl('employee-images') : asset('images/avatar.png');
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
     * Get currenct month working days
     * 
     * @return int
     */
    public function getMonthlyWorkingDaysAttribute()
    {
        return Timesheet::getWorkingDays($this->locationId, $this->shiftId, Carbon::now()->startOfMonth(), Carbon::now());
    }

    /**
     * Get current month present attribut
     * 
     * @return int
     */
    public function getMonthlyPresentAttribute()
    {
        return $this->attendances()
            ->whereMonth("date", Carbon::now()->format('m'))
            ->whereStatus(ConfirmStatus::CONFIRMED())
            ->count();
    }

    /**
     * Get monthly leave attribute
     * 
     * @return int
     */
    public function getMonthlyLeaveAttribute()
    {
        return $this->leaveDays()->whereHas('leave', function ($query) {
            $query->where('status', LeaveStatus::APPROVED());
        })->whereMonth('date', Carbon::now()->format('m'))->count();
    }

    /**
     * Get current month present attribute
     * 
     * @return int
     */
    public function getMonthlyAbsentAttribute()
    {
        return $this->monthlyWorkingDays - $this->monthlyPresent - $this->monthlyLeave;
    }

    /**
     * Get monthly late attribute
     * 
     * @return int
     */
    public function getMonthlyLateAttribute()
    {
        return $this->attendances()
            ->whereMonth("date", Carbon::now()->format('m'))
            ->where('late', '>', 0)
            ->whereStatus(ConfirmStatus::CONFIRMED())
            ->count();
    }

    /**
     * Get monthly early leave attribute
     * 
     * @return int
     */
    public function getMonthlyEarlyLeaveAttribute()
    {
        return $this->gatePasses()
            ->whereMonth("passed_at", Carbon::now()->format('m'))
            ->whereStatus(GatePassStatus::PASSED())
            ->where('early_leave', 1)->count();
    }

    /**
     * Get monthly gate pass attribute
     * 
     * @return int
     */
    public function getMonthlyGatePassesAttribute()
    {
        return $this->gatePasses()
            ->whereMonth("passed_at", Carbon::now()->format('m'))
            ->whereStatus(GatePassStatus::PASSED())
            ->count();
    }

    /**
     * Get monthly outside spent attribute
     * 
     * @return int
     */
    public function getMonthlyOutsideSpentAttribute()
    {
        $seconds = $this->gatePasses()
            ->whereMonth("passed_at", Carbon::now()->format('m'))
            ->whereNotNull('in')
            ->whereStatus(GatePassStatus::PASSED())
            ->sum('spent');
        return gmdate("H:i", $seconds);
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
            return self::setEagerLoads([])->orderBy('first_name')->get()->pluck('id', 'nameWithId');
        });
    }
}