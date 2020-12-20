<?php

namespace App\Nova;

use App\Facades\Helper;
use R64\NovaFields\JSON;
use App\Facades\Settings;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use App\Nova\ServiceInvoice;
use Illuminate\Http\Request;
use App\Enums\DispatchStatus;
use App\Enums\GatePassStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\MorphTo;
use App\Enums\DistributionStatus;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use App\Nova\Actions\PassGatePass;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\AssetDistributionInvoice;
use App\Nova\Filters\GatePassStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\GoodsGatePasses\MarkAsDraft;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\GoodsGatePasses\ConfirmGatePass;
use App\Nova\Actions\GoodsGatePasses\GenerateGatePass;

class GoodsGatePass extends Resource
{
    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\GoodsGatePass::class;

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
    public static $permissions = ['can pass', 'can confirm', 'can generate', 'can mark as draft'];

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
        return "Goods Passes";
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
        return 'fas fa-ticket-alt';
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
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'location' => ['name'],
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
            BelongsTo::make('Location')->sortable()
                ->exceptOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })
                ->sortable(),

            RouterLink::make('Number', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ])
                ->sortable(),

            Text::make('Invoice Type', function () {
                return Helper::getModelResource($this->resource->invoiceType)::singularLabel();
            })
                ->exceptOnForms()
                ->sortable(),

            Text::make('Invoice No', function () {
                return $this->resource->invoice->readableId;
            })
                ->exceptOnForms()
                ->sortable(),

            MorphTo::make('Invoice Type', 'invoice')->types([
                ServiceInvoice::class,
                AssetDistributionInvoice::class,
            ])
                ->nullable()
                ->searchable()
                ->hideFromIndex(),

            Json::make('Details', [
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
                return $this->approve()->exists() ? $this->approve->employee->name : null;
            })
                ->canSee(function () {
                    return $this->approve()->exists();
                })
                ->onlyOnDetail()
                ->sortable(),

            DateTime::make('Approved At', function () {
                return $this->approve()->exists() ? $this->approve->createdAt : null;
            })
                ->exceptOnForms()
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
                return $request->user()->hasPermissionTo('can pass goods gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can pass goods gate passes') || $request->user()->isSuperAdmin();
                })
                ->withoutConfirmation()
                ->onlyOnTableRow(),

            (new MarkAsDraft)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can mark as draft goods gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can mark as draft goods gate passes') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Mark As Draft')
                ->confirmText('Are you sure want to mark the gate pass as draft')
                ->onlyOnDetail(),

            (new ConfirmGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm goods gate passes') || $request->user()->isSuperAdmin();
            })
                ->confirmButtonText('Confirm'),

            (new GenerateGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can generate goods gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can generate goods gate passes') || $request->user()->isSuperAdmin();
                })
                ->withoutConfirmation()
                ->onlyOnDetail(),
        ];
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
    public static function relatableAssetDistributionInvoices(NovaRequest $request, $query)
    {
        return $query->where('status', DistributionStatus::CONFIRMED())
            ->doesntHave('goodsGatePass');
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
    public static function relatableServiceInvoices(NovaRequest $request, $query)
    {
        return $query->where('status', DispatchStatus::CONFIRMED())
            ->doesntHave('goodsGatePass');
    }
}
