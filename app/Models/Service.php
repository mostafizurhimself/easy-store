<?php

namespace App\Models;

use App\Traits\ActiveScope;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model implements HasMedia
{
    use LogsActivity, SoftDeletes, InteractsWithMedia, ActiveScope;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['totalRemainingQuantity'];



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
        $this->addMediaCollection('service-images')->singleFile();
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
        return $this->belongsTo(ServiceCategory::class, 'category_id')->withTrashed();
    }

    /**
     * Determines many-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'service_provider')->withTrashed();
    }

    /**
     * Get the model total remaining quantity attribute
     *
     * @return double
     */
    public function getTotalRemainingQuantityAttribute()
    {
        return $this->totalDispatchQuantity - $this->totalReceiveQuantity;
    }

    /**
     * Get the filter options of materials
     *
     * @return array
     */
    public static function filterOptions()
    {
        return Cache::remember('nova-service-filter-options', 3600, function () {
            $services = self::setEagerLoads([])->orderBy('name')->get(['id', 'name']);

            return $services->mapWithKeys(function ($service) {
                return [$service->name => $service->id];
            })->toArray();
        });
    }
}
