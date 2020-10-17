<?php

namespace App\Nova;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\DispatchStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Hidden;
use App\Nova\Filters\DateFilter;
use Laravel\Nova\Fields\HasMany;
use App\Nova\Lenses\ReceiveItems;
use Laravel\Nova\Fields\Currency;
use App\Nova\Lenses\DispatchItems;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\ServiceInvoices\Recalculate;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\ServiceInvoices\ConfirmInvoice;
use App\Nova\Actions\ServiceInvoices\GenerateInvoice;
use App\Nova\Filters\DispatchStatusFilter;

class ServiceInvoice extends Resource
{

    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ServiceInvoice::class;

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 3;

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can generate'];

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">07</span>Service Section';

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
        return "Location: {$this->location->name}";
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
        'provider' => ['name'],
    ];


    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Invoices";
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-file-invoice';
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
            RouterLink::make('Invoice', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ]),

            Date::make('Date')
                ->rules('required')
                ->default(Carbon::now())
                ->readonly(),

            Hidden::make('Date')
                ->default(Carbon::now())
                ->hideWhenUpdating(),


            BelongsTo::make('Location')->searchable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Trix::make('Description')
                ->rules('nullable', 'max:500'),

            Currency::make('Total Dispatch Amount')
                ->currency('BDT')
                ->exceptOnForms(),

            Currency::make('Total Receive Amount')
                ->currency('BDT')
                ->exceptOnForms(),

            BelongsTo::make('Provider', 'provider', 'App\Nova\Provider')->searchable(),

            Badge::make('Status')->map([
                DispatchStatus::DRAFT()->getValue()     => 'warning',
                DispatchStatus::CONFIRMED()->getValue() => 'info',
                DispatchStatus::PARTIAL()->getValue()   => 'danger',
                DispatchStatus::RECEIVED()->getValue()  => 'success',
            ])
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            HasMany::make('Dispatches', 'dispatches', 'App\Nova\ServiceDispatch'),
            HasMany::make('Receives', 'receives', 'App\Nova\ServiceReceive')

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
              LocationFilter::make('Location', 'location_id')->canSee(function($request){
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
            }),

            new DateFilter('date'),

            new DispatchStatusFilter,
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
            new DispatchItems,
            new ReceiveItems
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

            (new Recalculate)->canSee(function($request){
                return $request->user()->isSuperAdmin();
            })->canRun(function($request){
                return $request->user()->isSuperAdmin();
            }),

            (new ConfirmInvoice)->canSee(function($request){
                return $request->user()->hasPermissionTo('can confirm service invoices');
            })->confirmButtonText('Confirm'),

            (new GenerateInvoice)->canSee(function($request){
                return $request->user()->hasPermissionTo('can generate service invoices');
            })
            ->canRun(function($request){
                return $request->user()->hasPermissionTo('can generate service invoices') || $request->user()->isSuperAdmin();
            })
            ->confirmButtonText('Generate')
            ->confirmText('Are you sure want to generate invoice now?')
            ->onlyOnDetail(),
        ];
    }
}
