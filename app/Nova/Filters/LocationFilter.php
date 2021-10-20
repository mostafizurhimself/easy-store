<?php

namespace App\Nova\Filters;

use App\Models\Location;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use AwesomeNova\Filters\DependentFilter;

class LocationFilter extends DependentFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = "Location";

    /**
     * Attribute name of filter. Also it is key of filter.
     *
     * @var string
     */
    public $attribute = 'location_id';

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