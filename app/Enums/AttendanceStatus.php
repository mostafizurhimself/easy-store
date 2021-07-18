<?php

namespace App\Enums;

/**
 * @method static AttendanceStatus REGULAR()
 * @method static AttendanceStatus LATE()
 * @method static AttendanceStatus EARLY_LEAVE()
 * @method static AttendanceStatus OVERTIME()
 */
class AttendanceStatus extends Enum
{
    private const REGULAR     = 'regular';
    private const LATE        = 'late';
    private const EARLY_LEAVE = 'early_leave';
    private const OVERTIME    = 'overtime';
}