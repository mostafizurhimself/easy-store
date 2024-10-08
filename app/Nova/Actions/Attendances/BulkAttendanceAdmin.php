<?php

namespace App\Nova\Actions\Attendances;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Location;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Date;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laraning\NovaTimeField\TimeField;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;

class BulkAttendanceAdmin extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = "Bulk Attendance";

    /**
     * The number of models that should be included in each chunk.
     *
     * @var int
     */
    public static $chunkCount = 200000000;

    /**
     * Disables action log events for this action.
     *
     * @var bool
     */
    public $withoutActionEvents = true;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $employees = Employee::where('location_id', $fields->location)->withoutResigned()->get();
        if (!empty($fields->department)) {
            $employees = Employee::where('location_id', $fields->location)->where('department_id', $fields->department)->withoutResigned()->get();
        }

        foreach ($employees as $employee) {
            Attendance::create([
                'location_id' => $fields->location,
                'employee_id' => $employee->id,
                'shift_id'    => $fields->shift,
                'date'        => $fields->date,
                'in'          => $fields->in,
            ]);
        }

        return Action::message('Attendances has taken successfully.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Date::make('Date')
                ->default(Carbon::now()),

            Select::make('Location')
                ->options(Location::filterOptions())
                ->searchable(),

            AjaxSelect::make('Department')
                ->get('/locations/{location}/departments')
                ->parent('location')
                ->onlyOnForms(),

            AjaxSelect::make('Shift')
                ->rules('required')
                ->get('/locations/{location}/shifts')
                ->parent('location')
                ->onlyOnForms(),

            TimeField::make('In', 'in')
                ->required(),
        ];
    }
}