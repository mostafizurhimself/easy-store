<?php

namespace App\Nova\Actions\ProductRequisitions;

use Illuminate\Bus\Queueable;
use App\Enums\RequisitionStatus;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateRequisition extends Action
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
        if($models->first()->status != RequisitionStatus::DRAFT()){
            return Action::openInNewTab(route('requisitions.products', $models->first()));
        }

        return Action::danger('You can not generate requisition now.');
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
