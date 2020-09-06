<?php

namespace App\Enums;

/**
 * @method static ActiveStatus INACTIVE()
 * @method static ActiveStatus ACTIVE()
 */
class PaymentMethod extends Enum
{
    private const CASH          = 'cash';
    private const CHEQUE        = 'cheque';
    private const BANK_TRANSFER = 'bank transfer';
}
