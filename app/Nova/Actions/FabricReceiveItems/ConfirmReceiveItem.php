<?php

namespace App\Nova\Actions\FabricReceiveItems;

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
            if($model->unitId == $model->fabric->unitId)
            {
                // Check status is draft or not.
                if($model->status != PurchaseStatus::DRAFT()){
                    return Action::danger('Already Confirmed');
                }
                $model->fabric->increment('quantity', $model->quantity);
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
