<?php

namespace App\Enums;

/**
 * @method static ActiveStatus INACTIVE()
 * @method static ActiveStatus ACTIVE()
 */
class PurchaseStatus extends Enum
{
    private const DRAFT     = 'draft';
    private const CONFIRMED = 'confirmed';
    private const PARTIAL   = 'Partial';
    private const RECEIVED  = 'Received';
    private const BILLED    = 'Billed';
}
