<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
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
    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }
}
