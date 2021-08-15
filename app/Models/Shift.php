<?php

namespace App\Models;

use App\Facades\Timesheet;

class Shift extends Model
{

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['location'];


    /**
     * The attributes that should be mutated to array.
     *
     * @var array
     */
    protected $casts = [
        'opening_hours' => 'array',
    ];
}