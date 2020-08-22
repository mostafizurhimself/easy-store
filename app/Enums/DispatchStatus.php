<?php

namespace App\Enums;

/**
 * @method static ActiveStatus INACTIVE()
 * @method static ActiveStatus ACTIVE()
 */
class DispatchStatus extends Enum
{
    private const DRAFT     = 'draft';
    private const CONFIRMED = 'confirmed';
    private const PARTIAL   = 'partial';
    private const RECEIVED  = 'received';
}
