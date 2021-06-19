<?php

namespace App\Nova\Actions\EmployeeGatePasses;

use Carbon\Carbon;
use App\Enums\GatePassStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\DateTime;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckIn extends Action
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
            if ($model->status == GatePassStatus::PASSED()) {
                $model->in = $fields->in;
                $model->save();
            } else {
                return Action::danger('Can not check in now.');
            }
        }

        return Action::message('Employee checked in successfully');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            DateTime::make('In')
                ->rules('required'),
        ];
    }
}
