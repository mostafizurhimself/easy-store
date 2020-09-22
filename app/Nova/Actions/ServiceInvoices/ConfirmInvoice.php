<?php

namespace App\Nova\Actions\ServiceInvoices;

use App\Enums\DispatchStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Text;
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
        foreach($models as $model)
        {
            //increment total dispatch quantity
            foreach($model->dispatches as $dispatch){
                $dispatch->service->increment('total_dispatch_quantity', $dispatch->dispatchQuantity);

                //Update the dispatch status
                $dispatch->status = DispatchStatus::CONFIRMED();
                $dispatch->save();
            }

            //update the model status
            $model->status = DispatchStatus::CONFIRMED();
            $model->save();
        }

        return Action::message('Service invoice confirmed successfully.');
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
