<?php

namespace App\Nova;

use App\Enums\ActiveStatus;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\LocationFilter;
use Easystore\TextUppercase\TextUppercase;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;

class Expenser extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Expenser::class;

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
    public static $priority = 1;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

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
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
      return 'fas fa-user-tie';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'code'
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
            ID::make(__('ID'), 'id')->sortable()
                ->onlyOnIndex(),

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

            Text::make('Name')
                ->sortable()
                ->rules('required', 'string', 'max:45', 'alpha_space', 'multi_space')
                ->creationRules([
                    Rule::unique('expensers', 'name')->where('location_id', request()->get('location') ?? request()->user()->locationId)
                ])
                ->updateRules([
                    Rule::unique('expensers', 'name')->where('location_id', request()->get('location') ?? request()->user()->locationId)->ignore($this->resource->id)
                ])
                ->fillUsing(function($request, $model){
                    $model['name'] = Str::title($request->name);
                })
                ->help('Your input will be converted to title case. Exp: "title case" to "Title Case".'),

            TextUppercase::make('Code')
                ->sortable()
                ->help('If you want to generate code automatically, leave the field blank.')
                ->rules('nullable', 'string', 'max:20', 'space', 'alpha_num')
                ->creationRules([
                    Rule::unique('expensers', 'code')->where('location_id', request()->get('location') ?? request()->user()->locationId)
                ])
                ->updateRules([
                    Rule::unique('expensers', 'code')->where('location_id', request()->get('location') ?? request()->user()->locationId)->ignore($this->resource->id)
                ]),

            Images::make('Images', 'expenser-image')
                ->croppable(true)
                ->singleImageRules('max:5000', 'mimes:jpg,jpeg,png')
                ->hideFromIndex(),


            AjaxSelect::make('Employee ID', 'employee_id')
                ->rules('nullable')
                ->get('/locations/{location}/employees')
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

            BelongsTo::make('Employee ID', 'employee', 'App\Nova\Employee')
                ->exceptOnForms(),

            BelongsTo::make('Employee ID', 'employee', 'App\Nova\Employee')->searchable()
                ->onlyOnForms()
                ->nullable()
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

            Currency::make('Opening Balance')
                ->currency('BDT')
                ->rules('required', 'numeric', 'min:0')
                ->hideWhenUpdating()
                ->hideFromIndex(),

            Currency::make('Balance')
                ->currency('BDT')
                ->rules('required', 'numeric', 'min:0')
                ->exceptOnForms(),

            Select::make('Status')
                ->options(ActiveStatus::titleCaseOptions())
                ->default(ActiveStatus::ACTIVE())
                ->rules('required')
                ->onlyOnForms(),

            Badge::make('Status')->map([
                    ActiveStatus::ACTIVE()->getValue()   => 'success',
                    ActiveStatus::INACTIVE()->getValue() => 'danger',
                ])
                ->label(function(){
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
           LocationFilter::make('Location', 'location_id')->canSee(function($request){
                return $request->user()->isSuperAdmin() || $request->user()->hasPermissionTo('view any locations data');
            }),
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
        return [];
    }
}
