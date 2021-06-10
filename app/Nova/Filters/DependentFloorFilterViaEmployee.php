<?php

namespace App\Nova\Filters;

use App\Models\Floor;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use AwesomeNova\Filters\DependentFilter;

class DependentFloorFilterViaEmployee extends DependentFilter
{

    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = "Floor Filter";

    public $dependentOf = ['location_id'];

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
        return $query->whereHas('receiver', function($query)use($value){
            return $query->where('floor_id', $value);
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request, $filters = [])
    {
        return Floor::where('location_id', $filters['location_id'])
            ->orderBy('name')
            ->pluck('name', 'id');
    }
}
