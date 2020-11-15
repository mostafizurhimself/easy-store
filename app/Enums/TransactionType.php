<?php

namespace App\Enums;

/**
 * @method static TransactionType ADD()
 * @method static TransactionType WITHDRAW()
 */
class TransactionType extends Enum
{
    private const DEPOSITE = 'deposite';
    private const WITHDRAW = 'withdraw';
}
