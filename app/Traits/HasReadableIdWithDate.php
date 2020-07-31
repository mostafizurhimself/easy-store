<?php
namespace App\Traits;

use App\Facades\Helper;

trait HasReadableIdWithDate
{

    public static function bootHasReadableIdWithDate()
    {
        /**
         * Set the readable id after the resource created
         *
         * @return void
         */
        static::created(function($model){
            $last = self::all()->last()->readableId;
            $model->readableId = Helper::generateReadableIdWithDate($last, self::readableIdPrefix(), self::$readableIdLength);
            $model->save();
        });
    }


}
