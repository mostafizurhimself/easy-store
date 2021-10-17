<?php

namespace App\Models;

use App\Traits\CamelCasing;
use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\Model;

class Finishing extends Model
{
   use HasReadableIdWithDate, CamelCasing;

   /**
    * The attributes that are not mass assignable.
    *
    * @var array
    */
   protected $guarded = [];

   /**
    * The accessors to append to the model's array form.
    *
    * @var array
    */
   protected $appends = ['unitName'];


   /**
    * Set the model readable id prefix
    *
    * @var string
    */
   public static function readableIdPrefix()
   {
      return "F";
   }

   /**
    * Set the model readable id length
    *
    * @var int
    */
   protected static $readableIdLength = 5;

   /**
    * Determines one-to-many relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function invoice()
   {
      return $this->belongsTo(FinishingInvoice::class, 'invoice_id')->withTrashed();
   }

   /**
    * Determines one-to-many relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function product()
   {
      return $this->belongsTo(Product::class)->withTrashed();
   }

   /**
    * Determines one-to-many relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function style()
   {
      return $this->belongsTo(Style::class)->withTrashed();
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
}