<?php

namespace App\Models;

use App\Traits\ActiveScope;
use App\Enums\DistributionStatus;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fabric extends Model implements HasMedia
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $append = ['stock'];

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
       $this->addMediaCollection('fabric-images')->singleFile();
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
       return $this->belongsTo(FabricCategory::class, 'category_id')->withTrashed();
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
       return $this->hasMany(FabricDistribution::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function returnItems()
    {
       return $this->hasMany(FabricReturnItem::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferItems()
    {
       return $this->hasMany(FabricTransferItem::class);
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
                $this->transferItems()->draft()->sum('transfer_quantity') ;
    }

    /**
     * Get the filter options of fabrics
     *
     * @return array
     */
    public static function filterOptions()
    {
        return Cache::remember('nova-fabric-filter-options', 3600, function () {
            $fabrics = self::setEagerLoads([])->orderBy('name')->get(['id', 'name']);

            return $fabrics->mapWithKeys(function ($fabric) {
                return [$fabric->name => $fabric->id];
            })->toArray();
        });
    }

}
