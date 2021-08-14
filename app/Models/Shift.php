<?php

namespace App\Models;

use App\Facades\Timesheet;
use Spatie\Activitylog\Traits\LogsActivity;

class Shift extends Model
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
     * The attributes that should be mutated to array.
     *
     * @var array
     */
    protected $casts = [
        'opening_hours' => 'array',
    ];

    /**
     * Get opening hours object
     * 
     * @return \Spatie\OpeningHours\OpeningHours
     */
    public function getSpatieOpeningHoursAttribute()
    {
        return Timesheet::getOpeningHours($this->locationId, $this->id);
    }
}