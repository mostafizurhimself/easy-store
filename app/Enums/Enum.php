<?php

namespace App\Enums;

use Illuminate\Support\Str;
use MyCLabs\Enum\Enum as BaseEnum;

class Enum extends BaseEnum
{
    /**
     * Show the enum values in title case
     *
     * @return array
     */
    public static function titleCaseOptions()
    {
        $values = array();

        /** @psalm-var T $value */
        foreach (static::toArray() as $key => $value) {
            $values[$value] = Str::title(Str::of($value)->replace('_', ' '));
        }

        return $values;
    }

    /**
     * Get the title case of the value
     *
     * @return string
     */
    public function titleCase()
    {
        return Str::title(Str::of($this->value)->replace('_', ' '));
    }

     /**
     * Show the enum values in title case
     *
     * @return array
     */
    public static function filterOptions()
    {
        $values = array();

        /** @psalm-var T $value */
        foreach (static::toArray() as $key => $value) {
            $values[Str::title(Str::of($value)->replace('_', ' '))] = $value;
        }

        return $values;
    }
}
