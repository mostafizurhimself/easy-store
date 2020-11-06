<?php

namespace App\Nova\Actions\FabricReturnInvoices;

use App\Enums\ReturnStatus;
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

            if($model->status == ReturnStatus::CONFIRMED()){

                //Update the relate return items status
                foreach($model->returnItems as $returnItem){

                    if($returnItem->status == ReturnStatus::CONFIRMED()){

                        //increase the item quantity
                        $returnItem->fabric->increment('quantity', $returnItem->quantity);
                        $returnItem->status =  ReturnStatus::DRAFT();
                        $returnItem->save();
                    }
                }

                //Update the model status
                $model->approve()->forceDelete();
                $model->status = ReturnStatus::DRAFT();
                $model->save();
            }else{
                return Action::danger('Can not mark as draft.');
            }
        }

        return Action::message('Return invoice marked as draft successfully.');
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
