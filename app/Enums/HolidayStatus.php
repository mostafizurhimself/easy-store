<?php

namespace App\Enums;

/**
 * @method static HolidayStatus PUBLISHED()
 * @method static HolidayStatus UNPUBLISHED()
 */
class HolidayStatus extends Enum
{
    private const PUBLISHED   = 'published';
    private const UNPUBLISHED = 'unpublished';
}
