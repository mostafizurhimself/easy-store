<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class, 'department_id');
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
     * Get the filter options
     *
     * @return array
     */
    public static function filterOptions()
    {
        return Cache::remember('nova-department-filter-options', 3600, function () {
            $departments = self::setEagerLoads([])->orderBy('name')->get(['id', 'name']);

            return $departments->mapWithKeys(function ($department) {
                return [$department->name => $department->id];
            })->toArray();
        });
    }
}