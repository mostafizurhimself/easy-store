<?php

namespace App\Nova\Actions\MaterialReturnInvoices;

use App\Enums\ReturnStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        foreach($models as $model){
            //Check model has relation items
            if($model->returnItems()->count() == 0)
            {
                return Action::danger("No retun item added.");
            }

            if($model->status == ReturnStatus::DRAFT()){

                //Update the relate return items status
                foreach($model->returnItems as $returnItem){

                    if($returnItem->status == ReturnStatus::DRAFT()){

                        //Decrease the item quantity
                        $returnItem->material->decrement('quantity', $returnItem->quantity);
                        $returnItem->status =  ReturnStatus::CONFIRMED();
                        $returnItem->save();
                    }
                }


                //Update the model status
                $model->approve()->create(['employee_id' => $fields->approved_by]);
                $model->status = ReturnStatus::CONFIRMED();
                $model->save();
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
