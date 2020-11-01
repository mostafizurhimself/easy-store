<?php

namespace App\Nova\Actions\MaterialDistributions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use App\Enums\DistributionStatus;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmDistribution extends Action
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

             // Check model status is draft or not
             if($model->status == DistributionStatus::DRAFT()){

                 //Decrease the item quantity
                 $model->material->decrement('quantity', $model->quantity);

                 //Update the distribution status
                 $model->status = DistributionStatus::CONFIRMED();

                 //Save the model
                 $model->save();
             }else{
                 return Action::danger('Already Confirmed.');
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
