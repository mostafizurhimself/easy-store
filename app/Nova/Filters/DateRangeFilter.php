<?php

namespace App\Nova\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Ampeco\Filters\DateRangeFilter as Base;

class DateRangeFilter extends Base
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name;

    /**
     * The date column name of the filter.
     *
     * @var string
     */
    public $dateColumn;

    /**
     * Set the filterable date column
     *
     * @return void
     */
    public function __construct($dateColumn = "created_at", $name = "Date Between")
    {
        $this->dateColumn = $dateColumn;
        $this->name = $name;
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        // dd($value);
        $from = Carbon::parse($value[0])->startOfDay();
        $to = Carbon::parse($value[1])->endOfDay();

        return $query->whereBetween($this->dateColumn, [$from, $to]);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [];
    }
}