<?php

namespace App\Nova\Actions\FabricPurchaseOrders;

use App\Enums\PurchaseStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\FabricPurchaseOrderConfirmed;

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

                event(new FabricPurchaseOrderConfirmed($model));
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
