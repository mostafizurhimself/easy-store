<?php

namespace App\Nova\Actions\AssetDistributionReceiveItem;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use App\Enums\DistributionStatus;
use Illuminate\Support\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmReceiveItem extends Action
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
            $model->asset->increment('quantity', $model->quantity);
            $model->status = DistributionStatus::CONFIRMED();
            $model->save();
        }

        if($models->count() > 1){
            return Action::message('Receive Items are confirmed successfully.');
        }

        return Action::message('Receive Item is confirmed successfully.');
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
