<?php

namespace App\Nova\Actions\FabricPurchaseOrders;

use App\Enums\PurchaseStatus;
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
        foreach($models as $model){
            if($model->status == PurchaseStatus::CONFIRMED() && !$model->receiveItems()->exists()){

                //Update the relate purchase items status
                foreach($model->purchaseItems as $purchaseItem){
                    if($purchaseItem->status == PurchaseStatus::CONFIRMED()){
                        $purchaseItem->status =  PurchaseStatus::DRAFT();
                        $purchaseItem->save();
                    }
                }

                //Update the model status
                $model->approve()->forceDelete();
                $model->status = PurchaseStatus::DRAFT();
                $model->save();

            }else{
                return Action::danger('Can not mark as draft.');
            }
        }

        return Action::message('Purchase Order marked as draft successfully.');
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
