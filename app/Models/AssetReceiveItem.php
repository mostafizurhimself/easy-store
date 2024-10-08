<?php

namespace App\Models;

use App\Traits\CamelCasing;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetReceiveItem extends Model implements HasMedia
{
   use LogsActivity, SoftDeletes, CamelCasing, HasReadableIdWithDate, InteractsWithMedia;

   /**
    * The attributes that are not mass assignable.
    *
    * @var array
    */
   protected $guarded = [];

   /**
    * Set the model readable id prefix
    *
    * @var string
    */
   public static function readableIdPrefix()
   {
      return "RIA";
   }

   /**
    * Set the model readable id length
    *
    * @var int
    */
   protected static $readableIdLength = 6;

   /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
   protected $dates = ['date'];

   /**
    * The accessors to append to the model's array form.
    *
    * @var array
    */
   protected $appends = ['location', 'unitName'];

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
      $this->addMediaCollection('receive-item-attachments');
   }

   /**
    * Determines one-to-many relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function purchaseOrder()
   {
      return $this->belongsTo(AssetPurchaseOrder::class, 'purchase_order_id')->withTrashed();
   }

   /**
    * Determines one-to-many relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function purchaseItem()
   {
      return $this->belongsTo(AssetPurchaseItem::class, 'purchase_item_id')->withTrashed();
   }

   /**
    * Determines one-to-many relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function asset()
   {
      return $this->belongsTo(Asset::class, 'asset_id')->withTrashed();
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
    * Get the unit for the model
    *
    * @return string
    */
   public function getUnitNameAttribute()
   {
      return $this->unit->name;
   }

   /**
    * Get the location of the model
    *
    * @return string
    */
   public function getLocationAttribute()
   {
      return $this->purchaseOrder->location;
   }
}
