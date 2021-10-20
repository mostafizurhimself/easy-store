<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
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
   public function floor()
   {
      return $this->belongsTo(Floor::class)->withTrashed();
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
    * Determines one-to-many relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function subSections()
   {
      return $this->hasMany(SubSection::class)->withTrashed();
   }
}
