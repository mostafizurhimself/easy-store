<?php

namespace App\Nova\Actions\ServiceInvoices;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class Recalculate extends Action
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

            foreach($model->dispatches as $dispatch){
                //Update dispatch receive quantity
                $dispatch->updateReceiveQuantity();

                //Update dispatch receive amount
                $dispatch->updateReceiveAmount();

                //Update the dispatch status
                $dispatch->updateStatus();
            }

            // Calculate the invoice total dispatch amount
            $model->updateDispatchAmount();

            // Calculate the invoice total receive amount
            $model->updateReceiveAmount();

            //Update the invoice status
            $model->updateStatus();
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
