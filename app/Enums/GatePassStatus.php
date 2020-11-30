<?php

namespace App\Enums;

/**
 * @method static GatePassStatus DRAFT()
 * @method static GatePassStatus APPROVED()
 * @method static GatePassStatus PASSED()
 */
class GatePassStatus extends Enum
{
    private const DRAFT    = 'draft';
    private const APPROVED = 'approved';
    private const PASSED   = 'passed';
}
