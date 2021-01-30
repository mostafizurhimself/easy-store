<?php

namespace App\Enums;

/**
 * @method static LicenseStatus INACTIVE()
 * @method static LicenseStatus ACTIVE()
 * @method static LicenseStatus UNVERIFIED()
 */
class LicenseStatus extends Enum
{
    private const INACTIVE   = 'inactive';
    private const ACTIVE     = 'active';
    private const UNVERIFIED = 'unverified';
}
