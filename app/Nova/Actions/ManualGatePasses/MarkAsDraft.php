<?php

namespace App\Nova\Actions\ManualGatePasses;

use App\Enums\GatePassStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkAsDraft extends Action
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
            if ($model->status == GatePassStatus::CONFIRMED()) {
                // Remove approver
                $model->approve()->forceDelete();
                //Update the model status
                $model->status = GatePassStatus::DRAFT();
                $model->save();

                return Action::message('Mark as draft successfully.');
            } else {
                return Action::danger('Can not mark as draft.');
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
