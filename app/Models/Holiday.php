<?php

namespace App\Models;


class Holiday extends Model
{

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start', 'end'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['location'];
}