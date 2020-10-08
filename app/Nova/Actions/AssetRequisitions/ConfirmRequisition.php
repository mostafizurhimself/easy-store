<?php

namespace App\Nova\Actions\AssetRequisitions;

use App\Models\Role;
use App\Helpers\Settings;
use Illuminate\Bus\Queueable;
use App\Enums\RequisitionStatus;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\RequisitionConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class ConfirmRequisition extends Action
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
            if($model->status == RequisitionStatus::DRAFT()){

                //Update the relate requisition items status
                foreach($model->requisitionItems as $requisitionItem){
                    if($requisitionItem->status == RequisitionStatus::DRAFT()){
                        $requisitionItem->status =  RequisitionStatus::CONFIRMED();
                        $requisitionItem->save();
                    }
                }

                $model->status = RequisitionStatus::CONFIRMED();
                $model->save();

                //Notify the users
                $users = \App\Models\User::permission(['view asset requisitions', 'view any asset requisitions'])->where('location_id', $model->receiverId)->get();
                Notification::send($users, new RequisitionConfirmed(\App\Nova\AssetRequisition::uriKey(), $model));

                //Notify super admins
                if(Settings::superAdminNotification())
                {
                    $users = \App\Models\User::role(Role::SUPER_ADMIN)->get();
                    Notification::send($users, new RequisitionConfirmed(\App\Nova\AssetRequisition::uriKey(), $model));
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
        return [];
    }
}
