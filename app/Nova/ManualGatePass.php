<?php

namespace App\Nova;

use R64\NovaFields\JSON;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\GatePassStatus;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use App\Nova\Actions\ScanGatePass;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\Filters\DateRangeFilter;
use App\Nova\Filters\GatePassStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\ManualGatePasses\MarkAsDraft;
use App\Nova\Actions\ManualGatePasses\PassGatePass;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\ManualGatePasses\ConfirmGatePass;
use App\Nova\Actions\ManualGatePasses\GenerateGatePass;
use OptimistDigital\NovaSimpleRepeatable\SimpleRepeatable;

class ManualGatePass extends Resource
{
    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ManualGatePass::class;

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Gatepass Section';

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 4;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can pass', 'can confirm', 'can generate', 'can mark as draft'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'readable_id';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Manual Passes";
    }

    /**
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel()
    {
        return __('Create Gate Pass');
    }

    /**
     * Get the text for the update resource button.
     *
     * @return string|null
     */
    public static function updateButtonLabel()
    {
        return __('Update Gate Pass');
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-clipboard-list';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'readable_id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            BelongsTo::make('Location')
                ->searchable()
                ->sortable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            RouterLink::make('Number', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ])
                ->sortable(),


            Text::make('Receiver Name')
                ->sortable()
                ->rules('required', 'string', 'max:250'),

            SimpleRepeatable::make('Items', 'items', [
                Text::make('Description')
                    ->rules('required', 'string', 'max:250'),
                Number::make('Quantity')
                    ->rules('required', 'numeric', 'min:1'),
            ])
                ->canAddRows(true) // Optional, true by default
                ->canDeleteRows(true), // Optional, true by default

            Number::make('Total Quantity')
                ->sortable()
                ->exceptOnForms(),

            JSON::make('Summary', [
                Number::make('Total CTN', 'total_ctn')
                    ->rules('nullable', 'numeric', 'min:0')
                    ->default(0),

                Number::make('Total Poly', 'total_poly')
                    ->rules('nullable', 'numeric', 'min:0')
                    ->default(0),

                Number::make('Total Bag', 'total_bag')
                    ->rules('nullable', 'numeric', 'min:0')
                    ->default(0),
            ])
                ->flatten(),

            Textarea::make('Note')
                ->rules('nullable', 'max:500'),

            Text::make('Approved By', function () {
                return $this->approve ? $this->approve->employee->name : null;
            })
                ->canSee(function () {
                    return $this->approve;
                })
                ->exceptOnForms()
                ->sortable(),

            DateTime::make('Approved At', function () {
                return $this->approve ? $this->approve->createdAt : null;
            })
                ->onlyOnDetail()
                ->sortable(),

            BelongsTo::make('Passed By', 'passedBy', \App\Nova\User::class)
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->passedBy;
                }),

            DateTime::make('Passed At')
                ->onlyOnDetail()
                ->sortable(),

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

            new DateRangeFilter('passed_at', 'Passed Between'),
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
                return $request->user()->hasPermissionTo('can pass manual gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can pass manual gate passes') || $request->user()->isSuperAdmin();
                })
                ->withoutConfirmation()
                ->onlyOnTableRow(),

            (new MarkAsDraft)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can mark as draft manual gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can mark as draft manual gate passes') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Mark As Draft')
                ->confirmText('Are you sure want to mark the gate pass as draft')
                ->onlyOnDetail(),

            (new ConfirmGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm manual gate passes') || $request->user()->isSuperAdmin();
            })
                ->confirmButtonText('Confirm'),

            (new GenerateGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can generate manual gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can generate manual gate passes') || $request->user()->isSuperAdmin();
                })
                ->withoutConfirmation()
                ->onlyOnDetail(),

            (new ScanGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can pass manual gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can pass manual gate passes') || $request->user()->isSuperAdmin();
                })
                ->withoutConfirmation()
                ->onlyOnIndex()
                ->standalone(),
        ];
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

        return $query->with('location', 'approve.employee');
    }
}