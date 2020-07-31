<?php

namespace App\Nova\Actions\FabricPurchaseOrders;

use App\Enums\PurchaseStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ConfirmPurchase extends Action
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
            if($model->status == PurchaseStatus::DRAFT()){
                $model->status = PurchaseStatus::CONFIRMED();
                $model->save();

                //Update the relate purchase items statusr
                foreach($model->purchaseItems as $purchaseItem){
                    if($purchaseItem->status == PurchaseStatus::DRAFT()){
                        $purchaseItem->status =  PurchaseStatus::CONFIRMED();
                        $purchaseItem->save();
                    }
                }
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
