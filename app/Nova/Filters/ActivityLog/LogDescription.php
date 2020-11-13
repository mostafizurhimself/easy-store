<?php

namespace App\Nova\Filters\ActivityLog;

use App\Enums\LogType;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class LogDescription extends Filter
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
    public $name = "Description";

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
        return $query->where('description', $value);
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
            'Created' => LogType::CREATED(),
            'Updated' => LogType::UPDATED(),
            'Deleted' => LogType::DELETED(),
            'Restored' => LogType::RESTORED(),
        ];
    }
}
