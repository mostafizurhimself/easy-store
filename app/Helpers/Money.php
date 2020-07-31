<?php

namespace App\Helpers;

class Money
{
    /**
     * Format money in BDT
     */
    public static function formatInBdt($value)
    {
        return number_format($value) ." BDT";
    }
}
