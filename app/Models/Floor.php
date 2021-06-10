<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Floor extends Model
{
    use LogsActivity, SoftDeletes;

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
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    /**
     * Determines has-one-through relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function subSections()
    {
        return $this->hasManyThrough(SubSection::class, Section::class);
    }

    /**
     * Get the filter options of floor
     *
     * @return array
     */
    public static function filterOptions()
    {
        return Cache::remember('nova-floor-filter-options', 3600, function () {
            $floors = self::setEagerLoads([])->orderBy('name')->get(['id', 'name']);

            return $floors->mapWithKeys(function ($floor) {
                return [$floor->name => $floor->id];
            })->toArray();
        });
    }
}
