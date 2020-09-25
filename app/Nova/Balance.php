<?php

namespace App\Nova;

use Carbon\Carbon;
use R64\NovaFields\Trix;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use App\Enums\BalanceStatus;
use App\Enums\PaymentMethod;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphMany;
use Easystore\RouterLink\RouterLink;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\Balances\ConfirmBalance;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;

class Balance extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Balance::class;

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = '<span class="hidden">10</span>Expense Section';

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
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'readable_id',
    ];

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
      return 'fas fa-wallet';
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
            RouterLink::make('Payment Id', 'id')
            ->withMeta([
                'label' => $this->readableId,
            ]),

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


        Date::make('Date')
            ->rules('required')
            ->default(function($request){
                return Carbon::now();
            }),

        AjaxSelect::make('Expenser', 'expenser_id')
            ->rules('required')
            ->get('/locations/{location}/expensers')
            ->parent('location')
            ->onlyOnForms()
            ->showOnCreating(function($request){
                if($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()){
                    return true;
                }
                return false;
            })->showOnUpdating(function($request){
                if($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()){
                    return true;
                }
                return false;
            }),

        BelongsTo::make('Expenser', 'expenser', 'App\Nova\Expenser')
            ->exceptOnForms(),

        BelongsTo::make('Expenser', 'expenser', 'App\Nova\Expenser')
        ->searchable()
            ->onlyOnForms()
            ->hideWhenCreating(function($request){
                if($request->user()->hasPermissionTo('create all locations data') || $request->user()->isSuperAdmin()){
                    return true;
                }
                return false;
            })->hideWhenUpdating(function($request){
                if($request->user()->hasPermissionTo('update all locations data') || $request->user()->isSuperAdmin()){
                    return true;
                }
                return false;
            }),

        Trix::make('Description')
            ->rules('nullable', 'max:500'),

        Text::make('Reference')
            ->rules('nullable', 'string', 'max:200')
            ->hideFromIndex(),

        Currency::make('Amount')
            ->currency('BDT')
            ->rules('required', 'numeric', 'min:0'),

        Select::make('Payment Method', 'method')
            ->options(PaymentMethod::titleCaseOptions())
            ->rules('required')
            ->onlyOnForms(),

        Text::make('Payment Method', function(){
            return Str::title(Str::of($this->method)->replace('_', " "));
        })
            ->exceptOnForms(),

        Files::make('Attachments', 'balance-attachments')
            ->singleMediaRules('max:5000') // max 5000kb
            ->hideFromIndex(),

        Text::make('Approved By', function(){
                return $this->approve->employee->name;
            })
            ->canSee(function(){
                return $this->approve()->exists();
            })
            ->onlyOnDetail(),


        Badge::make('Status')->map([
                BalanceStatus::DRAFT()->getValue()   => 'warning',
                BalanceStatus::CONFIRMED()->getValue() => 'info',
            ])
            ->label(function(){
                return Str::title(Str::of($this->status)->replace('_', " "));
            }),

        MorphMany::make('Activities', 'activities', "App\Nova\ActivityLog"),

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
            (new ConfirmBalance)->canSee(function($request){
                return $request->user()->hasPermissionTo('can confirm balances');
            }),
        ];
    }
}
