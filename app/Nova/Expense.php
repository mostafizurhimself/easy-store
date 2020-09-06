<?php

namespace App\Nova;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use App\Enums\ExpenseStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use App\Nova\AssetPurchaseOrder;
use App\Rules\ExpenseAmountRule;
use Laravel\Nova\Fields\MorphTo;
use App\Nova\FabricPurchaseOrder;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphMany;
use App\Nova\MaterialPurchaseOrder;
use Easystore\RouterLink\RouterLink;
use App\Rules\ExpenseAmountRuleForUpdate;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\Expenses\ConfirmExpense;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Titasgailius\SearchRelations\SearchesRelations;

class Expense extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Expense::class;

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
    public static $priority = 3;

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
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'location' => ['name'],
        'expenser' => ['name'],
        'category' => ['name'],
    ];

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        $subtitle = "Expenser: {$this->expenser->name}";
        $subtitle .= ", Location: {$this->location->name}";
        return $subtitle;
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
      return 'fas fa-money-bill';
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
            RouterLink::make('Expense Id', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ]),

            BelongsTo::make('Location')
                // ->searchable()
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


            AjaxSelect::make('Category', 'category_id')
                ->rules('required')
                ->get('/locations/{location}/expense-categories')
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

            BelongsTo::make('Category', 'category', 'App\Nova\ExpenseCategory')
                ->exceptOnForms(),

            BelongsTo::make('Category', 'category', 'App\Nova\ExpenseCategory')
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

            Text::make('PO Number')
                ->rules('nullable', 'string', 'max:15')
                ->hideFromIndex(),

            Currency::make('Amount')
                ->currency('BDT')
                ->rules('required', 'numeric', 'min:0')
                ->onlyOnForms()
                ->creationRules(new ExpenseAmountRule($request->get('expenser_id')))
                ->updateRules(new ExpenseAmountRuleForUpdate($request->get('expenser_id'), $this->resource->amount))
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


            Currency::make('Amount')
                ->currency('BDT')
                ->rules('required', 'numeric', 'min:0')
                ->onlyOnForms()
                ->creationRules(new ExpenseAmountRule($request->get('expenser')))
                ->updateRules(new ExpenseAmountRuleForUpdate($request->get('expenser'), $this->resource->amount))
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

            Currency::make('Amount')
                ->currency('BDT')
                ->exceptOnForms(),

            Files::make('Attachments', 'receive-item-attachments')
                ->singleMediaRules('max:5000') // max 5000kb
                ->hideFromIndex(),

            Text::make('Approved By', 'approved_by')
                ->rules('required', 'string', 'max:200')
                ->onlyOnDetail(),

            Badge::make('Status')->map([
                    ExpenseStatus::DRAFT()->getValue()   => 'warning',
                    ExpenseStatus::CONFIRMED()->getValue() => 'info',
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
            new ConfirmExpense,
        ];
    }
}
