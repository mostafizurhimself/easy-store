<?php

namespace App\Nova;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\DispatchStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Easystore\RouterLink\RouterLink;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\ServiceDispatchInvoices\ConfirmInvoice;

class ServiceDispatchInvoice extends Resource
{

    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ServiceDispatchInvoice::class;

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
                ->default(function ($request) {
                    return Carbon::now();
                }),


            BelongsTo::make('Location')
                ->showOnCreating(function ($request) {
                    if ($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })->showOnUpdating(function ($request) {
                    if ($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })
                ->showOnDetail(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                })
                ->showOnIndex(function ($request) {
                    if ($request->user()->hasPermissionTo('view all locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            Trix::make('Description')
                ->rules('nullable', 'max:500'),

            Currency::make('Total Amount')
                ->currency('BDT')
                ->exceptOnForms(),

            BelongsTo::make('Provider', 'provider', 'App\Nova\Provider'),

            Badge::make('Status')->map([
                DispatchStatus::DRAFT()->getValue()     => 'warning',
                DispatchStatus::CONFIRMED()->getValue() => 'danger',
                DispatchStatus::PARTIAL()->getValue()   => 'info',
                DispatchStatus::RECEIVED()->getValue()  => 'success',
            ])
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            HasMany::make('Dispatches', 'dispatches', 'App\Nova\ServiceDispatch')

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
            new ConfirmInvoice,
        ];
    }
}
