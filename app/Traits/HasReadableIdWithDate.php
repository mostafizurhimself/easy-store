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
        static::creating(function($model){
            $last = null;

            if(self::all()->last()){
                $last = self::all()->last()->readableId;
            }

            $model->readableId = Helper::generateReadableIdWithDate($last, self::readableIdPrefix(), self::$readableIdLength);
        });
    }


}
