<?php

namespace App\Nova\Actions\Attendances;

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Employee;
use Michielfb\Time\Time;
use App\Models\Attendance;
use App\Models\Department;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Date;
use NovaAjaxSelect\AjaxSelect;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BulkAttendance extends Action
{
    use InteractsWithQueue, Queueable;

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
        $employees = Employee::where('location_id', Auth::user()->locationId)->get();
        if (!empty($fields->department)) {
            $employees = Employee::where('location_id', Auth::user()->locationId)->where('department_id', $fields->department)->get();
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

            Select::make('Department')
                ->options(Department::where('location_id', Auth::user()->locationId)->pluck('name', 'id')->toArray())
                ->searchable(),

            Select::make('Shift')
                ->options(Shift::where('location_id', Auth::user()->locationId)->pluck('name', 'id')->toArray())
                ->searchable(),

            Time::make('In', 'in')
                ->sortable()
                ->format('HH:mm')
                ->required(),
        ];
    }
}
