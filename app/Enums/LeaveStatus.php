<?php

namespace App\Enums;

/**
 * @method static LeaveStatus DRAFT()
 * @method static LeaveStatus CONFIRMED()
 * @method static LeaveStatus APPROVED()
 */
class LeaveStatus extends Enum
{
    private const DRAFT     = 'draft';
    private const CONFIRMED = 'confirmed';
    private const APPROVED  = 'approved';
}
