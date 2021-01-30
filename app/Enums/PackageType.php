<?php

namespace App\Enums;

/**
 * @method static PackageType STANDARD()
 * @method static PackageType PREMIUM()
 * @method static PackageType PROFESSIONAL()
 * @method static PackageType PLUS()
 */
class PackageType extends Enum
{
    private const STANDARD     = 'standard';
    private const PREMIUM      = 'premium';
    private const PROFESSIONAL = 'professional';
    private const PLUS         = 'plus';
}
