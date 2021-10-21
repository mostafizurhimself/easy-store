<?php

namespace App\Models;

use App\Models\Unit;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOutput extends Model
{
   use SoftDeletes;

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
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
   protected $dates = ['date'];

   /**
    * Determines one-to-many relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function category()
   {
      return $this->belongsTo(ProductCategory::class)->withTrashed();
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
   public function floor()
   {
      return $this->belongsTo(Floor::class)->withTrashed();
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
    * Get the unitName for the model
    *
    * @return string
    */
   public function getUnitNameAttribute()
   {
      return $this->unit->name;
   }
}