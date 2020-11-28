<?php

namespace App\Nova\Filters;

use App\Models\Location;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use AwesomeNova\Filters\DependentFilter;

class BelongsToDependentLocationFilter extends DependentFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = "Location Filter";

    /**
     * Relationship for the location
     *
     * @var string
     */
    public $relationship;

    /**
     * RelatedFilter constructor.
     * @param null   $name
     * @param null   $attribute
     * @param string $relationship
     */
    public function __construct($name = null, $attribute = null, $relationship)
    {
        $this->name = $name ?? $this->name;
        $this->attribute = $attribute ?? $this->attribute ?? str_replace(' ', '_', Str::lower($this->name()));
        $this->relationship = $relationship;
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
            $invoice->where('location_id', $value);
        });
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
