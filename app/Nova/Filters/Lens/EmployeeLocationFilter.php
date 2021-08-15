<?php

namespace App\Nova\Filters\Lens;

use App\Models\Location;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use AwesomeNova\Filters\DependentFilter;

class EmployeeLocationFilter extends DependentFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = "Location";

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
        return $query->where('employees.location_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request, array $filters = [])
    {
        return Location::filterOptions();
    }
}