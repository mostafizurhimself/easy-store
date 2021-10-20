<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
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
   public function subSection()
   {
      return $this->belongsTo(SubSection::class)->withTrashed();
   }
}