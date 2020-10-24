<?php

namespace App\Nova\Actions\MaterialTransferInvoices;

use App\Models\Role;
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
     * The number of models that should be included in each chunk.
     *
     * @var int
     */
    public static $chunkCount = 200000000;

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

            //Get all the transfer items of the transfer invoice
            foreach ($model->transferItems as $transferItem) {

                //Decrease the material quantity
                $transferItem->material->decrement('quantity', $transferItem->transferQuantity);

                //Update the transfer item status
                $transferItem->status = TransferStatus::CONFIRMED();
                $transferItem->save();
            }

            //Update the transfer invoice status
            $model->status = TransferStatus::CONFIRMED();
            $model->save();

            //Notify the users
            $users = \App\Models\User::permission(['view material transfer invoices', 'view any material transfer invoices'])->where('location_id', $model->receiverId)->get();
            Notification::send($users, new TransferConfirmed(\App\Nova\MaterialTransferInvoice::uriKey(), $model));

            //Notify super admins
            if(Settings::superAdminNotification())
            {
                $users = \App\Models\User::role(Role::SUPER_ADMIN)->get();
                Notification::send($users, new TransferConfirmed(\App\Nova\MaterialTransferInvoice::uriKey(), $model));
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
