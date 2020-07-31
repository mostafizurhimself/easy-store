<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

class Helper
{
    /**
     * Generates formated id with given value
     *
     * @param int
     * @return string
     */
    public function generateReadableId($value, $prefix = null, $length = 7)
    {
        return $prefix.\str_pad($value, $length, "0", STR_PAD_LEFT );;
    }

    /**
     * Generate a formated purchase order number
     *
     * @return string
     */
    public function generateReadableIdWithDate($last, $prefix, $length=5)
    {
        //Set initial value
        $value = 1;

        //Set the prefix with date
        $prefix = $prefix.Carbon::now()->format('ymd');

        //Parse the last value
        $lastValue = intval(substr($last, 9, $length));

        //Parse the last month
        $lastMonth = intval(substr($last, 5, 2));

        if($lastMonth == Carbon::now()->month){
            $value = $lastValue + 1;
        }

        return $this->generateReadableId($value, $prefix, $length);  // POF20071300001
    }
}
