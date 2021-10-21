<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SubSection extends Model
{
   use SoftDeletes;

   /**
    * The attributes that are not mass assignable.
    *
    * @var array
    */
   protected $guarded = [];

   /**
    * Determines one-to-many relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function department()
   {
      return $this->belongsTo(Department::class)->withTrashed();
   }

   /**
    * Determines one-to-many relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function section()
   {
      return $this->belongsTo(Section::class)->withTrashed();
   }

   /**
    * Determines one-to-many relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function employee()
   {
      return $this->belongsTo(Employee::class)->withTrashed();
   }

   /**
    * Determines has-one-through relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
    */
   public function floor()
   {
      return $this->hasOneThrough(Floor::class, Section::class);
   }
}