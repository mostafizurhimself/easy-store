<?php

namespace App\Models;

use App\Traits\CamelCasing;
use App\Enums\GatePassStatus;
use App\Traits\HasReadableIdWithDate;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsGatePass extends Model
{
    use LogsActivity, SoftDeletes, HasReadableIdWithDate, CamelCasing;

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
    protected $dates = ['passed_at'];

     /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "GGP";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 5;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['approve', 'invoice'];

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
     * Get the owning invoice model.
     */
    public function invoice()
    {
        return $this->morphTo();
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
}
