<?php

namespace App\Nova\Filters;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class BelongsToSupplierFilter extends Filter
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
    public $name = "Supplier";

      /**
     * Relationship for the location
     *
     * @var string
     */
    public $relationship;

    /**
     * Set the relationship
     *
     * @return void
     */
    public function __construct($relationship)
    {
        return $this->relationship = $relationship;
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
        return $query->whereHas($this->relationship, function ($invoice) use ($value) {
            $invoice->where('supplier_id', $value);
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Supplier::belongsToFilterOptions();
    }
}
