<?php

namespace App\Nova\Actions\ServiceTransferInvoices;

use App\Facades\Settings;
use App\Enums\TransferStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use App\Notifications\TransferConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class ConfirmInvoice extends Action
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

            if($model->transferItems()->count() == 0)
            {
                return Action::danger("No transfer item added.");
            }

            //Get all the distribution items of the distribution invoice
            foreach ($model->transferItems as $transferItem) {

                $transferItem->service->increment('total_dispatch_quantity', $transferItem->transferQuantity);

                //Update the transferItem status
                $transferItem->status = TransferStatus::CONFIRMED();
                $transferItem->save();
            }

            //Update the distribution invoice status
            $model->status = TransferStatus::CONFIRMED();
            $model->save();


            //Notify the users
            $users = \App\Models\User::permission(['view service transfer invoices', 'view any service transfer invoices'])->where('location_id', $model->receiverId)->get();
            Notification::send($users, new TransferConfirmed(\App\Nova\ServiceTransferInvoice::uriKey(), $model));

            //Notify super admins
            if(Settings::superAdminNotification())
            {
                $users = \App\Models\User::role(Role::SUPER_ADMIN)->get();
                Notification::send($users, new TransferConfirmed(\App\Nova\ServiceTransferInvoice::uriKey(), $model));
            }

            return Action::message('Service transfer invoice confirmed successfully.');
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
