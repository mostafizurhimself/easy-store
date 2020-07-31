<?php

namespace App\Models;

use Eminiarts\NovaPermissions\Role as Base;

class Role extends Base
{
    /**
     * The name of the super-admin role.
     *
     * @var string
     */
    const SUPER_ADMIN = 'super-admin';
}
