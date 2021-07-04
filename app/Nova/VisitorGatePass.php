<?php

namespace App\Nova;

use Carbon\Carbon;
use App\Facades\Settings;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\GatePassStatus;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use App\Nova\Actions\ScanGatePass;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\Filters\DateRangeFilter;
use Bissolli\NovaPhoneField\PhoneNumber;
use App\Nova\Filters\GatePassStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\VisitorGatePasses\PassGatePass;
use App\Nova\Actions\VisitorGatePasses\ConfirmGatePass;
use App\Nova\Actions\VisitorGatePasses\GenerateGatePass;

class VisitorGatePass extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\VisitorGatePass::class;

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Gatepass Section';

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can pass', 'can confirm', 'can generate'];

    /**
     * Show the resources related permissions or not
     *
     * @return bool
     */
    public static function showPermissions()
    {
        return Settings::isGatePassModuleEnabled();
    }

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 2;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'readable_id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'readable_id',
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Visitor Passes";
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-id-card-alt';
    }


    /**
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel()
    {
        return __('Create Pass');
    }

    /**
     * Get the text for the update resource button.
     *
     * @return string|null
     */
    public static function updateButtonLabel()
    {
        return __('Update Pass');
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
            RouterLink::make('NO', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ])
                ->sortable(),

            BelongsTo::make('Location')
                ->searchable()
                ->sortable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Text::make('Visitor Name')
                ->sortable()
                ->rules('required', 'string', 'max:250'),

            PhoneNumber::make('Mobile')
                ->withCustomFormats('+88 ### #### ####')
                ->onlyCustomFormats()
                ->hideFromIndex()
                ->rules('nullable', 'string'),

            Text::make('Card No')
                ->sortable()
                ->hideFromIndex()
                ->rules('nullable', 'string', 'max:250'),

            Textarea::make('Purpose')
                ->rules('nullable', 'max:500'),

            BelongsTo::make('Visit To', 'employee', \App\Nova\Employee::class)
                ->onlyOnDetail()
                ->sortable(),

            BelongsTo::make('Visit To', 'employee', \App\Nova\Employee::class)
                ->sortable()
                ->searchable()
                ->canSee(function ($request) {
                    if (!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            AjaxSelect::make('Visit To', 'visit_to')
                ->rules('nullable')
                ->get('/locations/{location}/employees')
                ->parent('location')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            DateTime::make('In')
                ->sortable()
                ->rules('required')
                ->default(Carbon::now()),

            DateTime::make('Out')
                ->sortable()
                ->exceptOnForms(),

            Badge::make('Status')->map([
                GatePassStatus::DRAFT()->getValue()     => 'warning',
                GatePassStatus::CONFIRMED()->getValue() => 'info',
                GatePassStatus::PASSED()->getValue()    => 'success',
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
        return [
            LocationFilter::make('Location', 'location_id')->canSee(function ($request) {
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
            }),

            new GatePassStatusFilter,

            new DateRangeFilter(),
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
        return [
            (new PassGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can pass visitor gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can pass visitor gate passes') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Pass')
                ->onlyOnTableRow(),

            (new ConfirmGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm visitor gate passes') || $request->user()->isSuperAdmin();
            })
                ->confirmButtonText('Confirm'),

            (new GenerateGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can generate visitor gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can generate visitor gate passes') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Generate')
                ->onlyOnDetail(),

            (new ScanGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can pass visitor gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can pass visitor gate passes') || $request->user()->isSuperAdmin();
                })
                ->withoutConfirmation()
                ->onlyOnIndex()
                ->standalone(),
        ];
    }
}
