<?php

/*
 * This file is part of the bolechen/nova-activitylog
 *
 * (c) Bole Chen <avenger@php.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use App\Nova\ActivityLog;

return [
    /*
     * Set the resource used for `nova-activitylog` package.
     */
    'resource' => ActivityLog::class,
];
