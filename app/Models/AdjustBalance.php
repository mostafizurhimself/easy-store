<?php

namespace App\Models;

use App\Traits\CamelCasing;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdjustBalance extends Model
{
    use LogsActivity, SoftDeletes, CamelCasing;

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
    protected $dates = ['date'];

    /**
     * Get the owning adjustable model.
     */
    public function adjustable()
    {
        return $this->morphTo();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Return adjust increment type as boolean
     *
     * @return bool
     */
    public function isIncremented()
    {
        return $this->amount > 0 ? true : false;
    }

    /**
     * Adjust the balance of the model
     *
     * @return void
     */
    public function adjust()
    {
        $this->adjustable()->increment('balance', $this->amount);
    }
}
