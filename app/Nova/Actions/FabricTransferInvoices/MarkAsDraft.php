<?php

namespace App\Nova\Actions\FabricTransferInvoices;

use App\Enums\TransferStatus;
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
        foreach ($models as $model) {

            if ($model->status == TransferStatus::CONFIRMED() && !$model->receiveItems()->exists()) {
                //Get all the transfer items of the transfer invoice
                foreach ($model->transferItems as $transferItem) {

                    //increase the fabric quantity
                    $transferItem->fabric->increment('quantity', $transferItem->transferQuantity);

                    //Update the transfer item status
                    $transferItem->status = TransferStatus::DRAFT();
                    $transferItem->save();
                }

                //Update the transfer invoice status
                $model->status = TransferStatus::DRAFT ();
                $model->save();
            }else{
                return Action::danger('Already confirmed.');
            }
        }

        return Action::message('Transfer invoice marked as draft successfully.');
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
