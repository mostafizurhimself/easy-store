<?php

namespace App\Nova\Filters;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\TransferStatus;
use Laravel\Nova\Filters\Filter;

class TransferStatusFilter extends Filter
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
        return $query->where('status', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        if(Str::contains($request->resource, "receive")){
            return [
                'Draft'     => TransferStatus::DRAFT(),
                'Confirmed' => TransferStatus::CONFIRMED(),
            ];
        }
        return TransferStatus::filterOptions();
    }
}
