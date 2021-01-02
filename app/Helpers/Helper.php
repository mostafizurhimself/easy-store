<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;
use League\Flysystem\Config;

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
        return $prefix . \str_pad($value, $length, "0", STR_PAD_LEFT);
    }

    /**
     * Generates formated employee id
     *
     * @param int
     * @return string
     */
    public function generateEmployeeId($value, $prefix = null)
    {
        //Generates equal length prefix
        $prefix =  \str_pad($prefix, 3, "0", STR_PAD_RIGHT);

        return $prefix . \str_pad($value, 5, "0", STR_PAD_LEFT);
    }

    /**
     * Generate a formated purchase order number
     *
     * @return string
     */
    public function generateReadableIdWithDate($last, $prefix, $length = 5)
    {
        //Set initial value
        $value = 1;

        //Set the prefix with date
        $finalPrefix = $prefix . Carbon::now()->format('ym');

        //Parse the last value
        $lastValue = intval(substr($last, strlen($finalPrefix), $length));

        //Parse the last month
        $lastMonth = intval(substr($last, (strlen($prefix) + 2), 2));

        //Set the value
        if ($lastMonth == Carbon::now()->month) {
            $value = $lastValue + 1;
        }

        return $this->generateReadableId($value, $finalPrefix, $length);  // POF200700001
    }

    /**
     * Format Currency
     *
     * @return string
     */
    public function currency($value)
    {
        return config('nova.currency', 'BDT') . " " . $this->currencyShort($value);
    }

    /**
     * Format Currency
     *
     * @return string
     */
    public function currencyShort($value)
    {
        return money($value, config('nova.currency', 'BDT'));
    }

    /**
     * Format Currency
     *
     * @return string
     */
    public function currencyPdf($value)
    {
        return config('nova.currency', 'BDT') . " " . $this->currencyShortPdf($value);
    }

    /**
     * Format Currency
     *
     * @return string
     */
    public function currencyShortPdf($value)
    {
        return $value . " /=";
    }

    /**
     * Get the model resource
     *
     * @param string  model with namespace
     * @return string resource with namespace
     */
    public function getModelResource($model)
    {
        return Str::replaceFirst('Models', 'Nova', $model);
    }

    /**
     * Get all date between two dates
     *
     * @return array
     */
    public function getAllDates($start, $end)
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        $dateRange = CarbonPeriod::create($start, $end);

        $dates = [];
        foreach ($dateRange as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

}
