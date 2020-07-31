<?php
namespace App\Traits;

use App\Facades\Helper;

trait HasReadableId
{

    public static function bootHasReadableId()
    {
        /**
         * Set the readable id after the resource created
         *
         * @return void
         */
        static::created(function($model){
            $model->readableId = Helper::generateReadableId($model->id, self::readableIdPrefix(), self::$readableIdLength);
            $model->save();
        });
    }


}
