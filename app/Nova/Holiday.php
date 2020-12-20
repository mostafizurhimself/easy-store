<?php

namespace App\Nova;

use Carbon\Carbon;
use App\Facades\Settings;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use App\Enums\HolidayStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Actions\Holidays\Publish;
use Laravel\Nova\Http\Requests\NovaRequest;

class Holiday extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Holiday::class;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can publish'];

    /**
     * Show the resources related permissions or not
     *
     * @return bool
     */
    public static function showPermissions()
    {
        return Settings::isTimesheetModuleEnabled();
    }

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Time Section';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        return "Location: " . $this->location->name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-house-user';
    }

    /**
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel()
    {
        return __('Add Holiday');
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
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('Location')
                ->searchable()
                ->sortable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Text::make('Name')
                ->rules('required', 'string', 'max:50')
                ->sortable(),

            Textarea::make('Description')
                ->rules('nullable', 'max:500'),

            Date::make('Start')
                ->rules('required')
                ->sortable(),

            Date::make('End')
                ->rules('required')
                ->sortable(),

            Hidden::make('Days')
                ->fillUsing(function ($request, $model) {
                    $start = Carbon::parse($request->input('start'));
                    $end = Carbon::parse($request->input('end'));
                    $model['days'] = $start->diffInDays($end) + 1;
                })
                ->onlyOnForms(),

            Text::make('Days')
                ->sortable()
                ->exceptOnForms(),

            Badge::make('Status')->map([
                HolidayStatus::PUBLISHED()->getValue()   => 'success',
                HolidayStatus::UNPUBLISHED()->getValue() => 'danger',
            ])
                ->sortable()
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),
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
        return [
            (new Publish)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can publish holidays') || $request->user()->isSuperAdmin();
            })
                ->confirmText('Are you sure want to publish the holiday?')
                ->confirmButtonText('Publish')
                ->onlyOnTableRow(),
        ];
    }
}
