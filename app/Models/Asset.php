<?php

namespace App\Models;

use App\Traits\ActiveScope;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model implements HasMedia
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
        $this->addMediaCollection('asset-images')->singleFile();
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
        return $this->belongsTo(AssetCategory::class, 'category_id')->withTrashed();
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
    public function consumes()
    {
        return $this->hasMany(AssetConsume::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseItems()
    {
        return $this->hasMany(AssetPurchaseItem::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receiveItems()
    {
        return $this->hasMany(AssetReceiveItem::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function distributionItems()
    {
        return $this->hasMany(AssetDistributionItem::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function returnItems()
    {
        return $this->hasMany(AssetReturnItem::class);
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
            $this->distributionItems()->draft()->sum('distribution_quantity') -
            $this->returnItems()->draft()->sum('quantity');
    }

    /**
     * Get the filter options of materials
     *
     * @return array
     */
    public static function filterOptions()
    {
        return Cache::remember('nova-asset-filter-options', 3600, function () {
            $assets = self::setEagerLoads([])->orderBy('name')->get(['id', 'name']);

            return $assets->mapWithKeys(function ($asset) {
                return [$asset->name => $asset->id];
            })->toArray();
        });
    }
}
