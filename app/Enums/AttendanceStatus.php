<?php

namespace App\Enums;

/**
 * @method static AttendanceStatus DRAFT()
 * @method static AttendanceStatus CONFIRMED()
 * @method static AttendanceStatus EARLY_LEAVE()
 * @method static AttendanceStatus OVERTIME()
 */
class AttendanceStatus extends Enum
{
    private const DRAFT       = 'draft';
    private const CONFIRMED   = 'confirmed';
    private const EARLY_LEAVE = 'early_leave';
    private const OVERTIME    = 'overtime';
}
