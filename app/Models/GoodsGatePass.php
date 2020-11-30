<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;

class GoodsGatePass extends Model
{
    use LogsActivity;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Add all attributes that are not listed in $guarded for log
     *
     * @var boolean
     */
    protected static $logUnguarded = true;

}
