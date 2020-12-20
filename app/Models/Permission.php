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

    /**
     * Scope a query to only include show permissions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShow($query)
    {
        return $query->where('show', 1);
    }
}
