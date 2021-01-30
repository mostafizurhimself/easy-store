<?php

namespace App\Enums;

/**
 * @method static ConfirmStatus DRAFT()
 * @method static ConfirmStatus CONFIRMED()
 */
class ConfirmStatus extends Enum
{
    private const DRAFT       = 'draft';
    private const CONFIRMED   = 'confirmed';
}
