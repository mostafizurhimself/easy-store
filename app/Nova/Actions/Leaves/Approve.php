<?php

namespace App\Nova\Actions\Leaves;

use App\Enums\LeaveStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Approve extends Action
{
    use InteractsWithQueue, Queueable;

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
        foreach ($models as $model) {
            if ($model->status == LeaveStatus::CONFIRMED()) {
                // Add approver
                $model->approve()->create(['employee_id' => $fields->approved_by]);
                //Update the model status
                $model->status = LeaveStatus::APPROVED();
                $model->save();
            } else {
                return Action::danger('Already approved.');
            }
        }


        if ($models->count() > 1) {
            return Action::message('Gate passes confirmed successfully.');
        }

        return Action::message('Gate pass confirmed successfully.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('Approved By')
                ->rules('required')
                ->options(\App\Models\Employee::approvers())
        ];
    }
}
