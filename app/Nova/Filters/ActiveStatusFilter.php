<?php

namespace App\Nova\Filters;

use App\Enums\ActiveStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ActiveStatusFilter extends Filter
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
    public $name = "Status";

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
        return $query->whereStatus($value)->withoutGlobalScopes([\App\Traits\ActiveScope::class]);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Active'   => ActiveStatus::ACTIVE(),
            'Inactive' => ActiveStatus::INACTIVE(),
        ];
    }
}
