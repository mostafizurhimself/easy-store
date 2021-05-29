<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;

class Attendance extends Model
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
    protected $dates = ['date'];

    /**
     * The attributes that should be mutated to array.
     *
     * @var array
     */
    protected $casts = [
        'opening_hour' => 'array',
    ];

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
    public function shift()
    {
        return $this->belongsTo(Shift::class)->withTrashed();
    }

    /**
     * Scope a query to only include late employees.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLate($query)
    {
        return $query->where('late', '>', 0);
    }

    /**
     * Scope a query to only include early leave employees.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEarlyLeave($query)
    {
        return $query->where('early_leave', '>', 0);
    }

    /**
     * Scope a query to only include overtime employees.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOvertime($query)
    {
        return $query->where('overtime', '>', 0);
    }

    /**
     * Scope a query to only include regular employees.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegular($query)
    {
        return $query->where('late', '=', 0)
                    ->where('early_leave', '=', 0);
    }
}
