<?php

namespace App\Enums;

/**
 * @method static ActiveStatus INACTIVE()
 * @method static ActiveStatus ACTIVE()
 */
class OutputStatus extends Enum
{
    private const DRAFT        = 'draft';
    private const CONFIRMED    = 'confirmed';
    private const ADD_TO_STOCK = 'add to stock';
}
