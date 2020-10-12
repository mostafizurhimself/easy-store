<?php

namespace App\Nova\Actions\FinishingInvoices;

use App\Enums\FinishingStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ConfirmFinishing extends Action
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
            if($model->finishings()->count() == 0)
            {
                return Action::danger("No finishing item added.");
            }


            //update the finishing status
            foreach($model->finishings as $finishing){
                $finishing->status = FinishingStatus::CONFIRMED();
                $finishing->save();
            }

            //update the invoice status
            $model->status = FinishingStatus::CONFIRMED();
            $model->save();
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
