<?php

namespace App\Nova\Actions\ServiceInvoices;

use App\Enums\DispatchStatus;
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
            // check if model status is confirmed or not
            if ($model->status == DispatchStatus::CONFIRMED() && !$model->receives()->exists()) {
                //decrement total dispatch quantity
                foreach ($model->dispatches as $dispatch) {
                    $dispatch->service->decrement('total_dispatch_quantity', $dispatch->dispatchQuantity);

                    //Update the dispatch status
                    $dispatch->status = DispatchStatus::DRAFT();
                    $dispatch->save();
                }

                //update the model status
                $model->status = DispatchStatus::DRAFT();
                $model->save();
            } else {
                return Action::danger("Can not mark as Draft.");
            }
        }
        return Action::message('Service invoice marked as draft successfully.');
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
