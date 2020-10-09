<?php

namespace App\Models;

use App\Traits\Authorize;
use App\Traits\CamelCasing;
use App\Traits\Locationable;
use Eminiarts\NovaPermissions\Role as Base;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Base
{
    use CamelCasing;

    /**
     * The name of the super-admin role.
     *
     * @var string
     */
    const SUPER_ADMIN = 'super-admin';

}
