<?php

namespace App\Enums;

/**
 * @method static GatepassType EMPLOYEE()
 * @method static GatepassType MANUAL()
 * @method static GatepassType GOODS()
 * @method static GatepassType VISITOR()
 */
class GatepassType extends Enum
{
    private const EMPLOYEE = 'employee';
    private const MANUAL   = 'manual';
    private const GOODS    = 'goods';
    private const VISITOR  = 'visitor';
}
