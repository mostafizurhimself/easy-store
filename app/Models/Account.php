<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
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
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function transactions()
   {
      return $this->hasMany(Transaction::class);
   }

   /**
    * Determines one-to-many relation
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function balances()
   {
      return $this->hasMany(Balance::class);
   }
}
