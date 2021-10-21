<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\SoftDeletes;

class FabricCategory extends Model
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
    public function fabrics()
    {
        return $this->hasMany(Fabric::class, 'category_id');
    }

    /**
     * Get the filter options of the model
     *
     * @return array
     */
    public static function filterOptions()
    {
        // Cache::forget('nova-fabric-category-filter-options');
        return Cache::remember('nova-fabric-category-filter-options', 3600, function () {
            return self::orderBy('name')->where('location_id', auth()->user()->locationId)->pluck('id', 'name');
        });
    }
}
