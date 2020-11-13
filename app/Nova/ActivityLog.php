<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Text;
use App\Traits\WithOutLocation;
use App\Nova\Filters\UserFilter;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\DateTime;
use App\Nova\Filters\DateRangeFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\ActivityLog\SubjectTypeFilter;
use App\Nova\Filters\ActivityLog\LogDescription;
use ChrisWare\NovaBreadcrumbs\Traits\Breadcrumbs;
use Bolechen\NovaActivitylog\Resources\Activitylog as BaseActivityLog;

class ActivityLog extends BaseActivityLog
{
    use Breadcrumbs;
    /**
     * The group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Super Admin';

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-calendar-alt';
    }

    /**
     * Hide resource from Nova's standard menu.
     *
     * @var bool
     */
    public static $displayInNavigation = true;

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Description')->sortable(),
            Text::make('Subject Id')->sortable(),
            Text::make('Subject Type')->sortable(),
            MorphTo::make('Causer')->sortable(),
            Text::make('Causer Ip', 'properties->ip')->onlyOnIndex()->sortable(),

            Code::make('Properties')->json(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            DateTime::make('Created At')->sortable(),
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
        return [
            new DateRangeFilter,
            new UserFilter,
            new LogDescription,
            new SubjectTypeFilter,
        ];
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
