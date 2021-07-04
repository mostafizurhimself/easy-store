<?php

namespace App\Nova\Actions\AssetPurchaseOrders;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Recalculate extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The text to be used for the action's confirm button.
     *
     * @var string
     */
    public $confirmButtonText = 'Recalculate';
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

            // Update reveive items
            foreach ($model->receiveItems as $receiveItem) {
                $receiveItem->amount = $receiveItem->rate * $receiveItem->quantity;
                $receiveItem->save();
            }

            foreach ($model->purchaseItems as $purchaseItem) {
                //Update purchase item receive quantity
                $purchaseItem->updateReceiveQuantity();

                //Update purchase item receive amount
                $purchaseItem->updateReceiveAmount();

                //Update the purchase item status
                $purchaseItem->updateStatus();
            }

            // Calculate the purchase total purchase amount
            $model->updatePurchaseAmount();

            // Calculate the purchase total receive amount
            $model->updateReceiveAmount();

            //Update the purchase status
            $model->updateStatus();
        }

        return Action::message('Recalculated successfully');
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
