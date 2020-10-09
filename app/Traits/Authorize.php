<?php
namespace App\Traits;

use Exception;

trait Authorize
{

    public static function bootAuthorize()
    {
        /**
         * Check the user has permission to create this resource
         *
         * @return void
         */
        static::creating(function($model){
            if(!empty(request()->user()->locationId) && ($model->locationId != request()->user()->locationId))
            {
                if(!request()->user()->hasPermissionTo('create all locations data'))
                {
                    throw new Exception('Sorry! You are not authorize to create this resource.');
                }
            }
        });
    }


}
