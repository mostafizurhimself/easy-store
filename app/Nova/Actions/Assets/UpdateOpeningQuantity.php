<?php

namespace App\Nova\Actions\Assets;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateOpeningQuantity extends Action
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
            $updatedQuantity = $fields->opening_quantity - $model->openingQuantity;

            //Set the opening quantity
            $model->openingQuantity = $fields->opening_quantity;

            //Updete the quantity
            $model->quantity = $model->quantity + $updatedQuantity;

            //Save the $model
            $model->save();
        }

        return Action::message('Opening Quantity updated successfully.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Number::make('Opening Quantity')
                ->rules('required', 'numeric', 'min:0'),
        ];
    }
}
