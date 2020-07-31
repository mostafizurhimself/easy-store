<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model implements HasMedia
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
       $this->addMediaCollection('asset-image')->singleFile();
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
       return $this->belongsTo(MaterialCategory::class, 'category_id');
    }

    /**
     * Determines many-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function suppliers()
    {
        return $this->belongsToMany( Supplier::class);
    }


}
