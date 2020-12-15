<?php

namespace App\Nova\Actions\Assets;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Consume extends Action
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
            if($model->quantity >= $fields->quantity){

                $model->consumes()->create([
                    'quantity' => $fields->quantity,
                    'rate'     => $model->rate,
                    'amount'   => $fields->quantity * $model->rate,
                    'user_id'  => Auth::user()->id,
                    'unit_id'  => $model->unitId
                ]);

                $model->decrement('quantity', $fields->quantity);
            }else{
                return Action::danger("You can not consume more than stock quantity");
            }
        }

        if($models->count() > 1){
            return Action::message('Assets are consumed successfully.');
        }

        return Action::message('Asset is consumed successfully.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Number::make('Quantity')
                ->rules('required', 'numeric', 'min:0'),
        ];
    }
}
