<?php

namespace App\Nova;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\PurchaseStatus;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Easystore\RouterLink\RouterLink;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\AssetPurchaseOrders\Recalculate;
use App\Nova\Actions\AssetPurchaseOrders\ConfirmPurchase;

class AssetPurchaseOrder extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\AssetPurchaseOrder';

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 3;

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
      return "Supplier: {$this->supplier->name}";
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
      return "Purchases";
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
        'supplier' => ['name'],
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
            RouterLink::make('PO Number', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ]),

            Date::make('Date')
                ->rules('required')
                ->default(function($request){
                    return Carbon::now();
                }),

            BelongsTo::make('Location')
                ->searchable()
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

            BelongsTo::make('Supplier')
                ->searchable(),

            Currency::make('Purchase Amount', 'total_purchase_amount')
                ->currency('BDT')
                ->exceptOnForms(),

            Currency::make('Receive Amount', 'total_receive_amount')
                ->currency('BDT')
                ->onlyOnDetail(),

            Badge::make('Status')->map([
                    PurchaseStatus::DRAFT()->getValue()     => 'warning',
                    PurchaseStatus::CONFIRMED()->getValue() => 'info',
                    PurchaseStatus::PARTIAL()->getValue()   => 'danger',
                    PurchaseStatus::RECEIVED()->getValue()  => 'success',
                    PurchaseStatus::BILLED()->getValue()    => 'danger',
                ])
                ->label(function(){
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),



            Trix::make('Note')
                ->rules('nullable', 'max:500'),

            HasMany::make('Purchase Items', 'purchaseItems', 'App\Nova\AssetPurchaseItem'),

            HasMany::make('Receive Items', 'receiveItems', 'App\Nova\AssetReceiveItem'),
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

            (new Recalculate)->canSee(function($request){
                return $request->user()->isSuperAdmin();
            })->canRun(function($request){
                return $request->user()->isSuperAdmin();
            }),

            new ConfirmPurchase
        ];
    }
}
