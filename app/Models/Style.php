<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Style extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
