<?php

namespace App\Nova;

use Carbon\Carbon;
use App\Enums\ReturnStatus;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Hidden;
use App\Nova\Lenses\ReturnItems;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\RouterLink\RouterLink;
use App\Nova\Filters\DateRangeFilter;
use App\Nova\Filters\ReturnStatusFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\AssetReturnInvoices\MarkAsDraft;
use App\Nova\Actions\AssetReturnInvoices\ConfirmInvoice;
use App\Nova\Actions\AssetReturnInvoices\GenerateInvoice;

class AssetReturnInvoice extends Resource
{
    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AssetReturnInvoice::class;

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">06</span>Asset Section';

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['can confirm', 'can generate', 'can mark as draft'];

    /**
     * The side nav menu order.
     *
     * @var int
     */
    public static $priority = 4;

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
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
      return "Return Invoices";
    }

    /**
     * Get the navigation label of the resource
     *
     * @return string
     */
    public static function navigationLabel()
    {
        return "Returns";
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
      return 'fas fa-undo-alt';
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
            RouterLink::make('Invoice', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ])
                ->sortable(),

            Date::make('Date')
                ->rules('required')
                ->default(Carbon::now())
                ->sortable()
                ->readonly(),

            Hidden::make('Date')
                ->default(Carbon::now())
                ->hideWhenUpdating(),

            BelongsTo::make('Location')
                ->searchable()
                ->sortable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Supplier', 'supplier', "App\Nova\Supplier")
                    ->searchable()
                    ->sortable(),

            Currency::make('Total Amount', 'total_return_amount')
                ->currency('BDT')
                ->sortable()
                ->exceptOnForms(),

            Badge::make('Status')->map([
                    ReturnStatus::DRAFT()->getValue()     => 'warning',
                    ReturnStatus::CONFIRMED()->getValue() => 'info',
                    ReturnStatus::BILLED()->getValue()    => 'danger',
                ])
                ->sortable()
                ->label(function(){
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),

            Trix::make('Note')
                ->rules('nullable', 'max:500'),

            Text::make('Approved By', function(){
                    return $this->approve ? $this->approve->employee->name : null;
                })
                ->canSee(function(){
                    return $this->approve()->exists();
                })
                ->onlyOnDetail(),

            HasMany::make('Return Items', 'returnItems', \App\Nova\AssetReturnItem::class),
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

            new DateRangeFilter('date'),

            new ReturnStatusFilter,
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
            new ReturnItems
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
            (new MarkAsDraft)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can mark as draft asset return invoices') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can mark as draft asset return invoices') || $request->user()->isSuperAdmin();
                })
                ->onlyOnDetail()
                ->confirmButtonText('Mark As Draft')
                ->confirmText('Are you sure want to mark the return invoice as draft?'),

            (new ConfirmInvoice)->canSee(function($request){
                return $request->user()->hasPermissionTo('can confirm asset return invoices');
            }),

            (new GenerateInvoice)->canSee(function($request){
                return $request->user()->hasPermissionTo('can generate asset return invoices');
            })
            ->canRun(function($request){
                return $request->user()->hasPermissionTo('can generate asset return invoices') || $request->user()->isSuperAdmin();
            })
            ->confirmButtonText('Generate')
            ->confirmText('Are you sure want to generate invoice now?')
            ->onlyOnDetail(),
        ];
    }
}
