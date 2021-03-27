<?php

namespace App\Nova\Actions\ManualGatePasses;

use App\Enums\GatePassStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmGatePass extends Action
{
    use InteractsWithQueue, Queueable;

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
            if ($model->status == GatePassStatus::DRAFT()) {
                // Add approver
                $model->approve()->create(['employee_id' => $fields->approved_by]);
                //Update the model status
                $model->status = GatePassStatus::CONFIRMED();
                $model->save();
            } else {
                return Action::danger('Already Confirmed.');
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
                ->options(\App\Models\Employee::gatePassApprovers())
        ];
    }
}
