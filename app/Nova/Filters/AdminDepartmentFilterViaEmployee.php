<?php

namespace App\Nova\Filters;

use App\Models\Department;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use AwesomeNova\Filters\DependentFilter;

class AdminDepartmentFilterViaEmployee extends DependentFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
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
        return $query->whereHas('employee', function ($query) use ($value) {
            $query->where('department_id', $value);
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
        return Department::where('location_id', $filters['location_id'])
            ->pluck('name', 'id');
    }
}
