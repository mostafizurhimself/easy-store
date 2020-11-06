<?php

namespace App\Nova\Actions\AssetDistributionInvoices;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use App\Enums\DistributionStatus;
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
        foreach ($models as $model) {

            if ($model->status == DistributionStatus::CONFIRMED() && !$model->receiveItems()->exists()) {
                //Get all the distribution items of the distribution invoice
                foreach ($model->distributionItems as $distributionItem) {

                    //Check if requisition exits
                    if ($distributionItem->requisitionId) {
                        // Update the related requisition item distribution quantity
                        $distributionItem->requisitionItem->updateDistributionQuantity();

                        // Update the related requisition item distribution amount
                        $distributionItem->requisitionItem->updateDistributionAmount();

                        // Update the related requisition item distribution status
                        $distributionItem->requisitionItem->updateStatus();
                    }

                    //increase the asset quantity
                    $distributionItem->asset->increment('quantity', $distributionItem->distributionQuantity);

                    //Update the distribution item status
                    $distributionItem->status = DistributionStatus::DRAFT();
                    $distributionItem->save();
                }

                //Check if requisition exits
                if ($model->requisitionId) {
                    //Update the related requisition distribution amount
                    $model->requisition->updateDistributionAmount();

                    //Update the related requisition distribution status
                    $model->requisition->updateStatus();
                }

                //Update the distribution invoice status
                $model->status = DistributionStatus::DRAFT();
                $model->save();
            }else{
                return Action::danger('Can not mark as draft.');
            }
        }

        return Action::message('Distribution Invoice marked as draft successfully.');
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
