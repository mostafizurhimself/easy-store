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
    private const PARTIAL   = 'partial';
    private const RECEIVED  = 'received';
    private const BILLED    = 'billed';
    // private const OVERFLOW  = 'overflow';
}
