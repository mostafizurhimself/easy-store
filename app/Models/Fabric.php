<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fabric extends Model implements HasMedia
{
    use LogsActivity, SoftDeletes, InteractsWithMedia;

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
       $this->addMediaCollection('fabric-image')->singleFile();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
       return $this->belongsTo(Unit::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
       return $this->belongsTo(FabricCategory::class, 'category_id');
    }

    /**
     * Determines many-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variations()
    {
       return $this->hasMany(FabricVariation::class, 'fabric_id');
    }

}
