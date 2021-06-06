<?php

namespace App\Enums;

/**
 * @method static PayrollStatus DRAFT()
 * @method static PayrollStatus CONFIRMED()
 * @method static PayrollStatus GENERATED()
 * @method static PayrollStatus LOCKED()
 */
class PayrollStatus extends Enum
{
    private const DRAFT      = 'draft';
    private const CONFIRMED  = 'confirmed';
    private const GENERATED  = 'generated';
    private const LOCKED     = 'locked';
}
