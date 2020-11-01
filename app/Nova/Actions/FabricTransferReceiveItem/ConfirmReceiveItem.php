<?php

namespace App\Nova\Actions\FabricTransferReceiveItem;

use App\Enums\TransferStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmReceiveItem extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The number of models that should be included in each chunk.
     *
     * @var int
     */
    public static $chunkCount = 200000000;

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

            // Check status is draft or not.
            if($model->status != TransferStatus::DRAFT()){
                return Action::danger('Already Confirmed');
            }
            $model->fabric->increment('quantity', $model->quantity);
            $model->status = TransferStatus::CONFIRMED();
            $model->save();
        }

        if ($models->count() > 1) {
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
