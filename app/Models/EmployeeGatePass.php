<?php

namespace App\Models;

use App\Enums\GatepassType;
use App\Traits\CamelCasing;
use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeGatePass extends Model
{
    use HasReadableIdWithDate, SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['in', 'out', 'approved_in', 'approved_out'];



    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['type', 'approvedInReadable', 'approvedOutReadable', 'inTimeReadable', 'outTimeReadable', 'approvedFor', 'approverName'];


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
        return $this->approvedIn ? $this->approvedIn->format("Y-m-d h:i A") : null;
    }

    /**
     * Get the approved out human reabable format
     *
     * @return string
     */
    public function getApprovedOutReadableAttribute()
    {
        return $this->approvedOut ? $this->approvedOut->format("Y-m-d h:i A") : null;
    }

    /**
     * Get the in time human reabable format
     *
     * @return string
     */
    public function getInTimeReadableAttribute()
    {
        return $this->in ? $this->in->format("Y-m-d h:i A") : null;
    }

    /**
     * Get the out time human reabable format
     *
     * @return string
     */
    public function getOutTimeReadableAttribute()
    {
        return $this->out ? $this->out->format("Y-m-d h:i A") : null;
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

    /**
     * Get approver name
     * 
     * @return string
     */
    public function getApproverNameAttribute()
    {
        return $this->approve ? $this->approve->employee->name : null;
    }
}