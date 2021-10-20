<?php

namespace App\Models;

use App\Enums\GatepassType;
use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitorGatePass extends Model
{
    use HasReadableIdWithDate, SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "VGP";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 5;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['in', 'out'];



    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['type'];

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'visit_to')->withTrashed();
    }

    /**
     * Get the gate pass type attribute
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        return GatepassType::VISITOR();
    }
}