<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use App\Models\FabricCategory;
use Laravel\Nova\Filters\Filter;
use AwesomeNova\Filters\DependentFilter;

class FabricCategoryFilter extends DependentFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = "Category";

    /**
     * Attribute name of filter. Also it is key of filter.
     *
     * @var string
     */
    public $attribute = 'category_id';

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request, array $filters = [])
    {
        return FabricCategory::orderBy('name')->where('location_id', auth()->user()->locationId)->pluck('name', 'id');
    }
}
