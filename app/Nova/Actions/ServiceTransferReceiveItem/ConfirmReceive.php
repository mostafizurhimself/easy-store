<?php

namespace App\Nova\Actions\ServiceTransferReceiveItem;

use App\Enums\TransferStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmReceive extends Action
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
        foreach($models as $model){
            if($model->unitId == $model->service->unitId)
            {
                $model->service->increment('total_receive_quantity', $model->quantity);
                $model->status = TransferStatus::CONFIRMED();
                $model->save();
            }else{
                return Action::danger("Unit mismatch! You can't confirm it now.");
            }
        }

        if($models->count() > 1){
            return Action::message('Service receives are confirmed successfully.');
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
