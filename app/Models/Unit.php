<?php

namespace App\Models;

use App\Traits\CamelCasing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use LogsActivity, CamelCasing, SoftDeletes;

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
     * Get the filter options of locations
     *
     * @return array
     */
    public static function selectOptions()
    {
        // Cache::forget('nova-unit-select-options');
        return Cache::remember('nova-unit-select-options', 3600 * 24, function () {
            return self::pluck('name', 'id')->toArray();
        });
    }
}