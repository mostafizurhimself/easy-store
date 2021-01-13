<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;

class Leave extends Model
{
    use LogsActivity;

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
    protected $dates = ['from', 'to'];

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
     * Get the model approve
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function approve()
    {
        return $this->morphOne(Approve::class, 'approvable');
    }
}
