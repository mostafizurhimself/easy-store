<?php

namespace App\Nova\Actions\ServiceReceives;

use App\Enums\DispatchStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ConfirmReceive extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The text to be used for the action's confirm button.
     *
     * @var string
     */
    public $confirmButtonText = 'Confirm';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach($models as $model){
            if($model->unitId == $model->service->unitId)
            {
                $model->service->increment('total_receive_quantity', $model->quantity);
                $model->status = DispatchStatus::CONFIRMED();
                $model->save();
            }else{
                return Action::danger("Unit mismatch! You can't confirm it now.");
            }
        }

        if($models->count() > 1){
            return Action::message('Service receives is confirmed successfully.');
        }

        return Action::message('Service receive is confirmed successfully.');
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
