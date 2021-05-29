<?php

namespace App\Nova\Filters;

use App\Enums\AttendanceStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class AttendanceStatusFilter extends Filter
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
        if ($value == AttendanceStatus::LATE()) {
            return $query->late();
        }

        if ($value == AttendanceStatus::EARLY_LEAVE()) {
            return $query->earlyLeave();
        }

        if ($value == AttendanceStatus::OVERTIME()) {
            return $query->overtime();
        }

        if($value == AttendanceStatus::REGULAR()){
            return $query->regular();
        }
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return AttendanceStatus::filterOptions();
    }
}
