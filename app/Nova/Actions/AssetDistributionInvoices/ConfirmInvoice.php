<?php

namespace App\Nova\Actions\AssetDistributionInvoices;

use App\Models\Role;
use App\Facades\Settings;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use App\Enums\DistributionStatus;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\DistributionConfirmed;
use Illuminate\Support\Facades\Notification;

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
        foreach ($models as $model) {

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

                //Decrease the asset quantity
                $distributionItem->asset->decrement('quantity', $distributionItem->distributionQuantity);

                //Update the distribution item status
                $distributionItem->status = DistributionStatus::CONFIRMED();
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
            $model->status = DistributionStatus::CONFIRMED();
            $model->save();

            //Notify the users
            $users = \App\Models\User::permission(['view asset distribution invoices', 'view any asset distribution invoices'])->where('location_id', $model->receiverId)->get();
            Notification::send($users, new DistributionConfirmed(\App\Nova\AssetDistributionInvoice::uriKey(), $model));

            //Notify super admins
            if(Settings::superAdminNotification())
            {
                $users = \App\Models\User::role(Role::SUPER_ADMIN)->get();
                Notification::send($users, new DistributionConfirmed(\App\Nova\AssetDistributionInvoice::uriKey(), $model));
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
