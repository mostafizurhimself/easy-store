<?php

namespace App\Nova;

use Carbon\Carbon;
use App\Rules\ReceiverRule;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\TransferStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class AssetTransferOrder extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\AssetTransferOrder';

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">06</span>Asset Section';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Transfers";
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-exchange-alt';
    }

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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Date::make('Date')
                ->rules('required')
                ->default(function ($request) {
                    return Carbon::now();
                }),

            // BelongsTo::make('Location')
            //     ->searchable()
            //     ->showOnCreating(function ($request) {
            //         if ($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()) {
            //             return true;
            //         }
            //         return false;
            //     })->showOnUpdating(function ($request) {
            //         if ($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()) {
            //             return true;
            //         }
            //         return false;
            //     })
            //     ->showOnDetail(function ($request) {
            //         if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
            //             return true;
            //         }
            //         return false;
            //     })
            //     ->showOnIndex(function ($request) {
            //         if ($request->user()->hasPermissionTo('view all locations data') || $request->user()->isSuperAdmin()) {
            //             return true;
            //         }
            //         return false;
            //     }),

            Select::make('Location', 'location_id')
                ->options(\App\Models\Location::pluck('name', 'id'))
                ->rules('required')
                ->onlyOnForms()
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

            Text::make('Location', function () {
                return $this->location->name;
            })
                ->exceptOnForms(),

            Currency::make('Total Amount')
                ->currency('BDT')
                ->exceptOnForms(),

            Trix::make('Note')
                ->nullable(),

            // BelongsTo::make('Receiver', 'receiver', "App\Nova\Location"),

            Select::make('Receiver', 'receiver_id')
                ->options(\App\Models\Location::pluck('name', 'id'))
                ->rules('required', new ReceiverRule($request->get('location_id')))
                ->onlyOnForms(),

            Text::make('Receiver', function () {
                return $this->receiver->name;
            })
                ->exceptOnForms(),

            Badge::make('Status')->map([
                TransferStatus::DRAFT()->getValue()     => 'warning',
                TransferStatus::CONFIRMED()->getValue() => 'info',
                TransferStatus::RECEIVED()->getValue()  => 'success',
            ])
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            HasMany::make('Transfer Items', 'transferItems', 'App\Nova\AssetTransferItem'),


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
            $query->where('location_id', $request->user()->locationId)->orWhere('receiver_id', $request->user()->locationId);
        }

        return $query;
    }
}
