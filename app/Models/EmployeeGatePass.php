<?php

namespace App\Models;

use App\Enums\GatepassType;
use App\Traits\CamelCasing;
use App\Traits\HasReadableIdWithDate;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeGatePass extends Model
{
    use LogsActivity, HasReadableIdWithDate, SoftDeletes;

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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['in', 'out', 'approved_in', 'approved_out'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['employee', 'approve'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['type', 'approvedInReadable', 'approvedOutReadable', 'inTimeReadable', 'outTimeReadable', 'approvedFor'];


    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "EGP";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 5;

    /**
     * Get the model approve
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function approve()
    {
        return $this->morphOne(Approve::class, 'approvable');
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function passedBy()
    {
        return $this->belongsTo(User::class, 'passed_by')->withTrashed();
    }

    /**
     * Get the gate pass type attribute
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        return GatepassType::EMPLOYEE();
    }

    /**
     * Get the approved in human reabable format
     *
     * @return string
     */
    public function getApprovedInReadableAttribute()
    {
        return $this->approvedIn ? $this->approvedIn->format("Y-m-d h:i:s A") : null;
    }

    /**
     * Get the approved out human reabable format
     *
     * @return string
     */
    public function getApprovedOutReadableAttribute()
    {
        return $this->approvedOut ? $this->approvedOut->format("Y-m-d h:i:s A") : null;
    }

    /**
     * Get the in time human reabable format
     *
     * @return string
     */
    public function getInTimeReadableAttribute()
    {
        return $this->in ? $this->in->format("Y-m-d h:i:s A") : null;
    }

    /**
     * Get the out time human reabable format
     *
     * @return string
     */
    public function getOutTimeReadableAttribute()
    {
        return $this->out ? $this->out->format("Y-m-d h:i:s A") : null;
    }

    /**
     * Get the approved for attribute
     *
     * @return string
     */
    public function getApprovedForAttribute()
    {
        if ($this->approvedIn && $this->approvedOut) {
            $duration = $this->approvedIn->diffInSeconds($this->approvedOut);
            return gmdate('H:i', $duration);
        }
    }
}
