<?php

namespace App\Models;

use App\Traits\Authorize;
use App\Traits\CamelCasing;
use App\Traits\Locationable;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use CamelCasing, Locationable, Authorize;

     /**
     * Set the model readable id prefix
     *
     * @return string
     */
    public static function readableIdPrefix()
    {
        return null;
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 5;
}
