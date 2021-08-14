<?php

namespace App\Nova\Filters;

use App\Models\Employee;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class EmployeeFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name;

    /**
     * The filterable column property.
     *
     * @var string
     */
    public $column;

    /**
     * Set the column and name
     *
     * @return void
     */
    public function __construct($column = "employee_id", $name = "Employee")
    {
        $this->column = $column;
        $this->name   = $name;
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
        return $query->where($this->column, $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Employee::filterOptions();
    }
}