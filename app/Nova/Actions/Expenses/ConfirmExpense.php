<?php

namespace App\Nova\Actions\Expenses;

use App\Enums\ExpenseStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmExpense extends Action
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

            //Set the approved by
            $model->approvedBy = $fields->approved_by;

            //Update the expenser balance
            $model->expenser->decrement('balance', $model->amount);

            //Update the status
            $model->status = ExpenseStatus::CONFIRMED();
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
        return [
            Text::make('Approved By', 'approved_by')
                ->rules('required', 'string', 'max:200')
        ];
    }
}
