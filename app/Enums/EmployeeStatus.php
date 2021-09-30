<?php

namespace App\Enums;

/**
 * @method static EmployeeStatus ACTIVE()
 * @method static EmployeeStatus INACTIVE()
 * @method static EmployeeStatus RESIGNED()
 * @method static EmployeeStatus VACATION()
 */
class EmployeeStatus extends Enum
{
    private const ACTIVE    = 'active';
    private const INACTIVE  = 'inactive';
    private const RESIGNED  = 'resigned';
    private const VACATION  = 'vacation';
}