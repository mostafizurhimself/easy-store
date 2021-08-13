<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\GatePassStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;
use App\Nova\Actions\ScanGatePass;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\Filters\DateRangeFilter;
use App\Nova\Filters\GatePassStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\GiftGatePasses\DownloadPdf;
use App\Nova\Actions\GiftGatePasses\MarkAsDraft;
use App\Nova\Actions\GiftGatePasses\PassGatePass;
use App\Nova\Actions\GiftGatePasses\DownloadExcel;
use App\Nova\Actions\GiftGatePasses\ConfirmGatePass;
use App\Nova\Actions\GiftGatePasses\GenerateGatePass;

class GiftGatePass extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\GiftGatePass::class;

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
    public static $priority = 1;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can pass', 'can confirm', 'can download',  'can generate',  'can mark as draft'];

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
        'readable_id', 'receiver_name', 'total',
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Gift Passes";
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
        return 'fas fa-gift';
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

            Number::make('Tshirt')
                ->default(0)
                ->rules('min:0')
                ->sortable()
                ->hideFromIndex(),

            Number::make('Polo Tshirt')
                ->default(0)
                ->rules('min:0')
                ->sortable()
                ->hideFromIndex(),

            Number::make('Shirt')
                ->default(0)
                ->rules('min:0')
                ->sortable()
                ->hideFromIndex(),

            Number::make('Gaberdine Pant')
                ->default(0)
                ->rules('min:0')
                ->sortable()
                ->hideFromIndex(),

            Number::make('Panjabi')
                ->default(0)
                ->rules('min:0')
                ->sortable()
                ->hideFromIndex(),

            Number::make('Kabli')
                ->default(0)
                ->rules('min:0')
                ->sortable()
                ->hideFromIndex(),

            Number::make('Total')
                ->default(0)
                ->rules('min:0')
                ->sortable()
                ->exceptOnForms(),

            Trix::make('Note')
                ->nullable()
                ->rules('max:1000'),

            Text::make('Approved By', function () {
                return $this->approve()->exists() ? $this->approve->employee->name : null;
            })
                ->canSee(function () {
                    return $this->approve()->exists();
                })
                ->exceptOnForms()
                ->sortable(),

            DateTime::make('Approved At', function () {
                return $this->approve()->exists() ? $this->approve->createdAt : null;
            })
                ->onlyOnDetail()
                ->sortable(),

            BelongsTo::make('Passed By', 'passedBy', \App\Nova\User::class)
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->passedBy;
                }),

            DateTime::make('Passed At')
                ->exceptOnForms()
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

            (new DownloadPdf)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download gift gate passes') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download gift gate passes') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download pdf?"),

            (new DownloadExcel)->onlyOnIndex()->canSee(function ($request) {
                return ($request->user()->hasPermissionTo('can download gift gate passes') || $request->user()->isSuperAdmin());
            })->canRun(function ($request) {
                return ($request->user()->hasPermissionTo('can download gift gate passes') || $request->user()->isSuperAdmin());
            })->confirmButtonText('Download')
                ->confirmText("Are you sure want to download excel?"),


            (new MarkAsDraft)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can mark as draft gift gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can mark as draft gift gate passes') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Mark As Draft')
                ->confirmText('Are you sure want to mark the gate pass as draft')
                ->onlyOnDetail(),


            (new ConfirmGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm gift gate passes') || $request->user()->isSuperAdmin();
            })
                ->confirmButtonText('Confirm'),

            (new GenerateGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can generate gift gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can generate gift gate passes') || $request->user()->isSuperAdmin();
                })
                ->withoutConfirmation()
                ->onlyOnDetail(),

            (new ScanGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can pass gift gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can pass gift gate passes') || $request->user()->isSuperAdmin();
                })
                ->withoutConfirmation()
                ->onlyOnIndex()
                ->standalone(),
        ];
    }
}