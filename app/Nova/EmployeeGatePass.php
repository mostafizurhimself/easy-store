<?php

namespace App\Nova;

use Carbon\Carbon;
use App\Facades\Settings;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Enums\GatePassStatus;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use App\Nova\Actions\ScanGatePass;
use Laravel\Nova\Fields\BelongsTo;
use Easystore\RouterLink\RouterLink;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\EmployeeGatePasses\CheckIn;
use App\Nova\Actions\EmployeeGatePasses\MarkAsDraft;
use App\Nova\Actions\EmployeeGatePasses\PassGatePass;
use App\Nova\Actions\EmployeeGatePasses\ConfirmGatePass;
use App\Nova\Actions\EmployeeGatePasses\GenerateGatePass;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;

class EmployeeGatePass extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\EmployeeGatePass::class;

    /**
     * The group associated with the resource.
     *
     * @return string
     */
    public static $group = 'Gatepass Section';

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
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Employee Passes";
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-house-user';
    }

    /**
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel()
    {
        return __('Create Pass');
    }

    /**
     * Get the text for the update resource button.
     *
     * @return string|null
     */
    public static function updateButtonLabel()
    {
        return __('Update Pass');
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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            RouterLink::make('NO', 'id')
                ->withMeta([
                    'label' => $this->readableId,
                ])
                ->sortable(),

            BelongsTo::make('Location')
                ->searchable()
                ->sortable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            BelongsTo::make('Employee', 'employee', \App\Nova\Employee::class)
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Employee', 'employee', \App\Nova\Employee::class)
                ->sortable()
                ->searchable()
                ->canSee(function ($request) {
                    if (!$request->user()->hasPermissionTo('view any locations data') || !$request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            AjaxSelect::make('Employee', 'employee_id')
                ->rules('required')
                ->get('/locations/{location}/employees')
                ->parent('location')
                ->onlyOnForms()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            DateTime::make('Approved Out')
                ->sortable()
                ->rules('required')
                ->hideFromIndex(),

            DateTime::make('Out')
                ->sortable()
                ->exceptOnForms(),

            Boolean::make('Early Leave', 'early_leave')
                ->onlyOnForms(),

            NovaDependencyContainer::make([
                DateTime::make('Approved In')
                    ->sortable()
                    ->hideFromIndex()
                    ->rules('nullable')
            ])->dependsOn('early_leave', 0),

            DateTime::make('In')
                ->sortable()
                ->exceptOnForms(),

            Textarea::make('Reason')
                ->rules('nullable', 'max:500'),

            Boolean::make('Early Leave', 'early_leave')
                ->exceptOnForms(),

            Text::make('Approved By', function () {
                return $this->approve()->exists() ? $this->approve->employee->name : null;
            })
                ->canSee(function () {
                    return $this->approve()->exists();
                })
                ->onlyOnDetail()
                ->sortable(),

            BelongsTo::make('Passed By', 'passedBy', \App\Nova\User::class)
                ->onlyOnDetail()
                ->canSee(function () {
                    return $this->passedBy;
                }),

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
            (new PassGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can pass employee gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can pass employee gate passes') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Pass')
                ->onlyOnTableRow(),

            (new CheckIn)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can pass employee gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can pass employee gate passes') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Check In')
                ->onlyOnTableRow(),

            (new MarkAsDraft)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can mark as draft employee gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can mark as draft employee gate passes') || $request->user()->isSuperAdmin();
                })
                ->confirmButtonText('Mark As Draft')
                ->confirmText('Are you sure want to mark the gate pass as draft')
                ->onlyOnDetail(),

            (new ConfirmGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can confirm employee gate passes') || $request->user()->isSuperAdmin();
            })
                ->confirmButtonText('Confirm'),

            (new GenerateGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can generate employee gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can generate employee gate passes') || $request->user()->isSuperAdmin();
                })
                ->withoutConfirmation()
                ->onlyOnDetail(),

            (new ScanGatePass)->canSee(function ($request) {
                return $request->user()->hasPermissionTo('can pass employee gate passes') || $request->user()->isSuperAdmin();
            })
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('can pass employee gate passes') || $request->user()->isSuperAdmin();
                })
                ->withoutConfirmation()
                ->onlyOnIndex()
                ->standalone(),
        ];
    }
}
