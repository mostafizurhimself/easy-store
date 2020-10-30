<?php

namespace App\Enums;

/**
 * @method static AdjustType INCREMENT()
 * @method static AdjustType DECREMENT()
 */
class AdjustType extends Enum
{
    private const INCREMENT = 'increment';
    private const DECREMENT = 'decrement';
}
