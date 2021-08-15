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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['location'];


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
        return Cache::remember('nova-fabric-category-filter-options', 3600, function () {
            $models = self::setEagerLoads([])->orderBy('name')->get(['id', 'name']);

            return $models->mapWithKeys(function ($model) {
                return [$model->name => $model->id];
            })->toArray();
        });
    }
}