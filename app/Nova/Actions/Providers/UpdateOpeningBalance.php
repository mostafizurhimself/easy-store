<?php

namespace App\Nova\Actions\Providers;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\Currency;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateOpeningBalance extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The text to be used for the action's confirm button.
     *
     * @var string
     */
    public $confirmButtonText = 'Update';

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
            //Calculate the updated value
            $updatedBalance = $fields->opening_balance - $model->openingBalance;

            //Set the opening balance
            $model->openingBalance = $fields->opening_balance;

            //Updete the balance
            $model->balance = $model->balance + $updatedBalance;

            //Save the $model
            $model->save();
        }

        return Action::message('Opening Balance updated successfully.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Currency::make('Opening Balance')
                ->rules('required', 'numeric', 'min:0')
                ->currency('BDT'),
        ];
    }
}
