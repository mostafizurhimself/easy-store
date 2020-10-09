<?php

namespace App\Models;

use App\Traits\Authorize;
use App\Traits\CamelCasing;
use App\Traits\Locationable;
use Eminiarts\NovaPermissions\Role as Base;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Base
{
    use CamelCasing, Authorize, Locationable, SoftDeletes;

    /**
     * The name of the super-admin role.
     *
     * @var string
     */
    const SUPER_ADMIN = 'super-admin';

    /**
     * Identify the role is global or not
     *
     * @return bool
     */
    public function isGlobal()
    {
        return $this->locationId ? true : false;
    }
}
