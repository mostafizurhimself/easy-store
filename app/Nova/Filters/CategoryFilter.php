<?php

namespace App\Nova\Filters;

use App\Models\AssetCategory;
use App\Models\FabricCategory;
use App\Models\MaterialCategory;
use App\Models\ProductCategory;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class CategoryFilter extends Filter
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
    public $name = "Category";

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
        return $query->where('category_id', $value)->withoutGlobalScopes();
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        if($request->resource == \App\Nova\Fabric::uriKey())
        {
            return FabricCategory::pluck('id', 'name');
        }

        if($request->resource == \App\Nova\Material::uriKey())
        {
            return MaterialCategory::pluck('id', 'name');
        }

        if($request->resource == \App\Nova\Asset::uriKey())
        {
            return AssetCategory::pluck('id', 'name');
        }

        if($request->resource == \App\Nova\Product::uriKey())
        {
            return ProductCategory::pluck('id', 'name');
        }

        if($request->resource == \App\Nova\Service::uriKey())
        {
            return ServiceCategory::pluck('id', 'name');
        }

        return [];
    }
}
