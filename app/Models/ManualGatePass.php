<?php

namespace App\Models;

use App\Enums\GatepassType;
use App\Traits\CamelCasing;
use App\Enums\GatePassStatus;
use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManualGatePass extends Model
{
    use SoftDeletes, HasReadableIdWithDate, CamelCasing;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['location'];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['passed_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['type'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'items'   => 'array',
        'summary' => 'array',
    ];

    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "MGP";
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
    public function passedBy()
    {
        return $this->belongsTo(User::class, 'passed_by', 'id')->withTrashed();
    }

    /**
     * Check the model status is confirmed or not
     *
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->status == GatePassStatus::CONFIRMED();
    }

    /**
     * Check the model status is draft or not
     *
     * @return bool
     */
    public function isDraft()
    {
        return $this->status == GatePassStatus::DRAFT();
    }

    /**
     * Get the gate pass type attribute
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        return GatepassType::MANUAL();
    }
}