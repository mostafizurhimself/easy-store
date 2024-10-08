<?php

namespace App\Nova;

use App\Facades\Settings;
use Illuminate\Support\Str;
use Laravel\Nova\Resource as NovaResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use ChrisWare\NovaBreadcrumbs\Traits\Breadcrumbs;

abstract class Resource extends NovaResource
{
    use Breadcrumbs;

    /**
     * The group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Others Section';

    /**
     * Default ordering for index query.
     *
     * @var array
     */
    public static $sort = [
        'id' => 'desc'
    ];

    /**
     * Indicates whether Nova should check for modifications between viewing and updating a resource.
     *
     * @var bool
     */
    public static $trafficCop = false;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = [];

    // /**
    //  * Prepare search column value.
    //  *
    //  * @param  string  $column
    //  * @param  string  $search
    //  * @return string
    //  */
    // protected static function searchableKeyword($column, $search)
    // {
    //     return $search . '%';
    // }


    /**
     * Show the resources related permissions or not
     *
     * @return bool
     */
    public static function showPermissions()
    {
        return true;
    }

    /**
     * Get the logical group associated with the resource.
     *
     * @return string
     */
    public static function group()
    {
        return "<span class='hidden'>" . config('group-order')[static::$group] . "</span>" . static::$group;;
    }

    /**
     * Get the navigation label of the resource
     *
     * @return string
     */
    public static function navigationLabel()
    {
        return static::label();
    }

    /**
     * Get the human readable name of the resource
     *
     * @return string
     */
    public static function readableName()
    {
        return str_replace('-', ' ', static::uriKey());
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];

            $query->orderBy(key(static::$sort), reset(static::$sort));
        }

        if ($request->user()->locationId && !$request->user()->hasPermissionTo('view any locations data')) {
            $query->where('location_id', $request->user()->location_id);
        }

        return $query;
    }

    /**
     * Build a Scout search query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Scout\Builder  $query
     * @return \Laravel\Scout\Builder
     */
    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a "detail" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query);
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return Str::singular(static::label());
    }

    /**
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel()
    {
        return __('Create :resource', ['resource' => static::singularLabel()]);
    }
}