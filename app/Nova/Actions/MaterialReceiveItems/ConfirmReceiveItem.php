<?php

namespace App\Nova\Actions\MaterialReceiveItems;

use Illuminate\Bus\Queueable;
use App\Enums\PurchaseStatus;
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
            if($model->unitId == $model->material->unitId)
            {
                $model->material->increment('quantity', $model->quantity);
                $model->status = PurchaseStatus::CONFIRMED();
                $model->save();
            }else{
                return Action::danger("Unit mismatch! You can't confirm it now.");
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
