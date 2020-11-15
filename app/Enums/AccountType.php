<?php

namespace App\Enums;

/**
 * @method static AccountType BANK()
 * @method static AccountType HANDCASH()
 */
class AccountType extends Enum
{
    private const BANK = 'bank';
    private const CASH = 'cash';
}
