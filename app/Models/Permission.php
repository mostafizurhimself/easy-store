<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as Base;

class Permission extends Base
{
    /**
     * The name of the super-admin group.
     *
     * @var string
     */
    const SUPER_ADMIN_GROUP = 'super admin';
}
