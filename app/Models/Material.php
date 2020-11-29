<?php

namespace App\Models;

use App\Traits\ActiveScope;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model implements HasMedia
{
    use LogsActivity, SoftDeletes, InteractsWithMedia, ActiveScope;

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
    protected $with = ['unit'];

    /**
     * Add all attributes that are not listed in $guarded for log
     *
     * @var boolean
     */
    protected static $logUnguarded = true;

    /**
     * Register the media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('material-images')->singleFile();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(MaterialCategory::class, 'category_id')->withTrashed();
    }

    /**
     * Determines many-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function distributions()
    {
        return $this->hasMany(MaterialDistribution::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function returnItems()
    {
        return $this->hasMany(MaterialReturnItem::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferItems()
    {
        return $this->hasMany(MaterialTransferItem::class);
    }

    /**
     * Get the model adjust quantities
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function adjustQuantities()
    {
        return $this->morphMany(AdjustQuantity::class, 'adjustable');
    }

    /**
     * Get the remaining stock attribute of the item
     *
     * @return double
     */
    public function getStockAttribute()
    {
        return $this->quantity -
            $this->distributions()->draft()->sum('quantity') -
            $this->returnItems()->draft()->sum('quantity') -
            $this->transferItems()->draft()->sum('transfer_quantity');
    }

    /**
     * Get the filter options of materials
     *
     * @return array
     */
    public static function filterOptions()
    {
        return Cache::remember('nova-material-filter-options', 3600, function () {
            $materials = self::setEagerLoads([])->orderBy('name')->get(['id', 'name']);

            return $materials->mapWithKeys(function ($material) {
                return [$material->name => $material->id];
            })->toArray();
        });
    }
}
