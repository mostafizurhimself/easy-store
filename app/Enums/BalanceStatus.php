<?php

namespace App\Enums;

/**
 * @method static ActiveStatus INACTIVE()
 * @method static ActiveStatus ACTIVE()
 */
class BalanceStatus extends Enum
{
    private const DRAFT     = 'draft';
    private const CONFIRMED = 'confirmed';
}
