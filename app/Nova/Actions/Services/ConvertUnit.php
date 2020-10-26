<?php

namespace App\Nova\Actions\Services;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ConvertUnit extends Action
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
            if($model->unitId == $fields->unit){
                return Action::danger('Same unit value, conversion failed!');
            }else{
                if($model->totalDispatchQuantity != 0){
                    $model->totalDispatchQuantity = round($model->totalDispatchQuantity / $fields->conversion_rate, 2);
                }

                if($model->totalReceiveQuantity != 0){

                    $model->totalReceiveQuantity = round($model->totalReceiveQuantity / $fields->conversion_rate, 2);
                }

                $model->unitId = $fields->unit;
                $model->save();
            }
        }

        return Action::message('Unit converted successfully.');
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
