<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;

class FabricCategory extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\FabricCategory';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 1;

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        $subtitle = "Total Materials: {$this->fabrics->count()},";
        $subtitle .= " Location: {$this->location->name}";
        return $subtitle;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name'
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'location' => ['name'],
    ];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">04</span>Fabrics Section';

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
      return 'fas fa-network-wired';
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
      return "Categories";
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Location')
                ->searchable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'string', 'max:45')
                ->creationRules([
                    Rule::unique('fabric_categories', 'name')->where('location_id', request()->get('location'))
                ])
                ->updateRules([
                    Rule::unique('fabric_categories', 'name')->where('location_id', request()->get('location'))->ignore($this->resource->id)
                ]),

            Textarea::make('Description')
                ->nullable()
                ->rules('max:200'),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
