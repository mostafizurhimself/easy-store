<?php
namespace App\Traits;

use Carbon\Carbon;

trait HasDate
{
    /**
     * Set the location id of the model
     */
    public static function bootHasDate()
    {
        static::creating(function($model){
            $model->date = Carbon::now();
        });
    }

}
