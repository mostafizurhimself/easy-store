<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetCategory extends Model
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
    public function assets()
    {
        return $this->hasMany(Asset::class, 'category_id');
    }

    /**
     * Get the filter options
     *
     * @return array
     */
    public static function filterOptions()
    {
        // Cache::forget('nova-asset-category-filter-options');
        return Cache::remember('nova-asset-category-filter-options', 3600, function () {
            return self::orderBy('name')->where('location_id', auth()->user()->locationId)->pluck('id', 'name');
        });
    }
}
