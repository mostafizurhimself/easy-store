<?php

namespace App\Enums;

/**
 * @method static ActiveStatus INACTIVE()
 * @method static ActiveStatus ACTIVE()
 */
class FinishingStatus extends Enum
{
    private const DRAFT     = 'draft';
    private const CONFIRMED = 'confirmed';
    private const ADDED     = 'added';
}
