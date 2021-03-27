<?php

namespace App\Nova\Actions\ManualGatePasses;

use Carbon\Carbon;
use App\Enums\GatePassStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PassGatePass extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = "Pass";

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
                //Update the model status
                $model->passedAt = Carbon::now();
                $model->passedBy = Auth::user()->id;
                $model->status   = GatePassStatus::PASSED();
                $model->save();
            } else {
                return Action::danger('Can not pass the gate pass.');
            }
        }

        return Action::message('Gate pass passed successfully.');
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
