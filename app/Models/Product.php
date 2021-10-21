<?php

namespace App\Models;

use App\Traits\ActiveScope;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements HasMedia
{
   use LogsActivity, SoftDeletes, InteractsWithMedia, ActiveScope;

   /**
    * The attributes that are not mass assignable.
    *
    * @var array
    */
   protected $guarded = [];

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
      $this->addMediaCollection('product-images')->singleFile();
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
      return $this->belongsTo(ProductCategory::class, 'category_id')->withTrashed();
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
}
