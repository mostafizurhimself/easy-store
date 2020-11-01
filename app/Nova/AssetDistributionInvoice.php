<?php

namespace App\Nova;

use Carbon\Carbon;
use Eminiarts\Tabs\Tabs;
use App\Rules\ReceiverRule;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Select;
use App\Nova\Filters\DateFilter;
use Illuminate\Support\Optional;
use Laravel\Nova\Fields\HasMany;
use App\Enums\DistributionStatus;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\Lenses\DistributionItems;
use App\Nova\Lenses\DistributionHistory;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\DistributionStatusFilter;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\AssetDistributionInvoice\AutoReceive;
use App\Nova\Actions\AssetDistributionInvoices\ConfirmInvoice;
use App\Nova\Actions\AssetDistributionInvoices\GenerateInvoice;
use App\Nova\Lenses\AssetDistributionInvoice\DistributionInvoices;

class AssetDistributionInvoice extends Resource
{
    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AssetDistributionInvoice::class;

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 6;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can generate', 'can auto receive'];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">06</span>Asset Section';


    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'readable_id';

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
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Distributions";
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-truck';
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
        'receiver' => ['name'],
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
            (new Tabs("Distribution Details", [
                "Distribution Info" => [
                    RouterLink::make('Invoice No', 'id')
                        ->sortable()
                        ->withMeta([
                            'label' => $this->readableId,
                        ]),

                    Date::make('Date')
                        ->rules('required')
                        ->default(Carbon::now())
                        ->sortable()
                        ->readonly(),

                    Hidden::make('Date')
                        ->default(Carbon::now())
                        ->hideWhenUpdating(),

                    BelongsTo::make('Location')->sortable()
                        ->searchable()
                        ->canSee(function ($request) {
                            if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                                return true;
                            }
                            return false;
                        }),

                    Currency::make('Distribution Amount', 'total_distribution_amount')
                        ->currency('BDT')
                        ->sortable()
                        ->exceptOnForms(),

                    Currency::make('Receive Amount', 'total_receive_amount')
                        ->currency('BDT')
                        ->sortable()
                        ->exceptOnForms(),

                    Trix::make('Note')
                        ->rules('nullable', 'max:500'),

                    Files::make('Attachments', 'distribution-attachments')
                        ->singleMediaRules('max:5000') // max 5000kb
                        ->hideFromIndex(),

                    Select::make('Receiver', 'receiver_id')
                        ->options(function () {
                            return \App\Models\Location::all()->whereNotIn('id', [request()->user()->locationId])->pluck('name', 'id');
                        })
                        ->rules('required', new ReceiverRule($request->get('location') ?? $request->user()->locationId))
                        ->searchable()
                        ->onlyOnForms(),

                    Text::make('Receiver', function () {
                        return $this->receiver->name;
                    })->sortable(),


                    AjaxSelect::make('Requisition', 'requisition_id')
                        ->get('/locations/{receiver_id}/asset-requisitions')
                        ->parent('receiver_id')
                        ->onlyOnForms(),

                    BelongsTo::make('Requisition', 'requisition', "App\Nova\AssetRequisition")
                        ->searchable()
                        ->sortable()
                        ->exceptOnForms(),

                    Badge::make('Status')->map([
                        DistributionStatus::DRAFT()->getValue()     => 'warning',
                        DistributionStatus::CONFIRMED()->getValue() => 'info',
                        DistributionStatus::PARTIAL()->getValue()   => 'danger',
                        DistributionStatus::RECEIVED()->getValue()  => 'success',
                    ])
                        ->sortable()
                        ->label(function () {
                            return Str::title(Str::of($this->status)->replace('_', " "));
                        }),
                ],

                "Receive Items" => [
                    HasMany::make('Receive Items', 'receiveItems', \App\Nova\AssetDistributionReceiveItem::class),
                ],
            ]))->withToolbar(),

            HasMany::make('Distribution Items', 'distributionItems', \App\Nova\AssetDistributionItem::class),
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

            new DateFilter('date'),

            new DistributionStatusFilter,
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
        return [
            new DistributionInvoices,
            new DistributionItems,
        ];
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
            (new AutoReceive)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can auto receive asset distribution invoices') || $request->user()->isSuperAdmin();
            })
            ->canRun(function ($request) {
                return $request->user()->hasPermissionTo('can auto receive asset distribution invoices') || $request->user()->isSuperAdmin();
            })
            ->confirmButtonText('Auto Receive')
            ->confirmText("Are you sure want to auto receive this invoice?")
            ->onlyOnDetail(),

            (new ConfirmInvoice)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm asset distribution invoices') || $request->user()->isSuperAdmin();
            }),

            (new GenerateInvoice)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can generate asset distribution invoices') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can generate asset distribution invoices') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Generate')
                ->confirmText('Are you sure want to generate invoice now?')
                ->onlyOnDetail(),
        ];
    }
}
