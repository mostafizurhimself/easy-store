<?php

namespace App\Nova\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Ampeco\Filters\DateRangeFilter;

class MultipleDateRangeFilter extends DateRangeFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = "Date Between";

    /**
     * The tables name and date column of the filter.
     *
     * @var array
     */
    public $params;

    /**
     * Set the filterable table and date column
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
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
        $from = Carbon::parse($value[0])->startOfDay();
        $to = Carbon::parse($value[1])->endOfDay();

        foreach($this->params as $param){
            $table  = $param['table'];
            $column = $param['column'];
            $query->whereBetween("{$table}.{$column}", [$from, $to]);
        }

        return $query;
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
