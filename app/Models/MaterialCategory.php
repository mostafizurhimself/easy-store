<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialCategory extends Model
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
    public function materials()
    {
        return $this->hasMany(Material::class, 'category_id');
    }

    /**
     * Get the filter options of the model
     *
     * @return array
     */
    public static function filterOptions()
    {
        // Cache::forget('nova-material-category-filter-options');
        return Cache::remember('nova-material-category-filter-options', 3600, function () {
            return self::orderBy('name')->where('location_id', auth()->user()->locationId)->pluck('id', 'name');
        });
    }
}
