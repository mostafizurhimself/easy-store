<?php

namespace App\Enums;

/**
 * @method static LogType CREATED()
 * @method static LogType UPDATED()
 * @method static LogType DELETED()
 */
class LogType extends Enum
{
    private const CREATED  = 'created';
    private const UPDATED  = 'updated';
    private const DELETED  = 'deleted';
    private const RESTORED = 'restored';
}
