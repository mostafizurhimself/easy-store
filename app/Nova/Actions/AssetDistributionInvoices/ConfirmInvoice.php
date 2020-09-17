<?php

namespace App\Nova\Actions\AssetDistributionInvoices;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use App\Enums\DistributionStatus;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmInvoice extends Action
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

            //Get all the distribution items of the distribution invoice
            foreach($model->distributionItems as $distributionItem){

                // Update the related requisition item distribution quantity
                $distributionItem->requisitionItem->updateDistributionQuantity();

                // Update the related requisition item distribution amount
                $distributionItem->requisitionItem->updateDistributionAmount();

                // Update the related requisition item distribution status
                $distributionItem->requisitionItem->updateStatus();

                //Decrease the asset quantity
                $distributionItem->asset->decrement('quantity', $distributionItem->distributionQuantity);

                //Update the distribution item status
                $distributionItem->status = DistributionStatus::CONFIRMED();
                $distributionItem->save();
            }

            //Update the related requisition distribution amount
            $model->requisition->updateDistributionAmount();

            //Update the related requisition distribution status
            $model->requisition->updateStatus();

            //Update the distribution invoice status
            $model->status = DistributionStatus::CONFIRMED();
            $model->save();
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
