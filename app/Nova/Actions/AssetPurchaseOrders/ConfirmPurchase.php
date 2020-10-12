<?php

namespace App\Nova\Actions\AssetPurchaseOrders;

use App\Models\Role;
use App\Facades\Settings;
use App\Enums\PurchaseStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PurchaseOrderConfirmed;

class ConfirmPurchase extends Action
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

            if($model->purchaseItems()->count() == 0)
            {
                return Action::danger("No purchase item added.");
            }

            if($model->status == PurchaseStatus::DRAFT()){

                //Update the relate purchase items statusr
                foreach($model->purchaseItems as $purchaseItem){
                    if($purchaseItem->status == PurchaseStatus::DRAFT()){
                        $purchaseItem->status =  PurchaseStatus::CONFIRMED();
                        $purchaseItem->save();
                    }
                }

                //Update the model status
                $model->approve()->create(['employee_id' => $fields->approved_by]);
                $model->status = PurchaseStatus::CONFIRMED();
                $model->save();

                //Notify the users
                $users = \App\Models\User::permission(['view asset purchase orders', 'view any asset purchase orders'])->where('location_id', $model->locationId)->get();
                Notification::send($users, new PurchaseOrderConfirmed(\App\Nova\AssetPurchaseOrder::uriKey(), $model));

                //Notify super admins
                if(Settings::superAdminNotification())
                {
                    $users = \App\Models\User::role(Role::SUPER_ADMIN)->get();
                    Notification::send($users, new PurchaseOrderConfirmed(\App\Nova\AssetPurchaseOrder::uriKey(), $model));
                }
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
        return [
            Select::make('Approved By')
                ->rules('required')
                ->options(\App\Models\Employee::approvers())
        ];
    }
}
