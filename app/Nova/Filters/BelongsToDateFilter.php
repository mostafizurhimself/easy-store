<?php

namespace App\Nova\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Ampeco\Filters\DateRangeFilter;

class BelongsToDateFilter extends DateRangeFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = "Date Between";

    /**
     * @var string
     */
    public $relationship;

    /**
     * Set the relationship
     *
     * @return void
     */
    public function __construct($relationship)
    {
        return $this->relationship = $relationship;
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
        return $query->whereHas($this->relationship, function ($invoice) use ($value) {
            $from = Carbon::parse($value[0])->startOfDay();
            $to = Carbon::parse($value[1])->endOfDay();

            $invoice->whereBetween('date', [$from, $to]);
        });
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
