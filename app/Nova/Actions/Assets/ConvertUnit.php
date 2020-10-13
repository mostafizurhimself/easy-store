<?php

namespace App\Nova\Actions\Assets;

use App\Models\Unit;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
                if($model->openingQuantity != 0){
                    $model->openingQuantity = round($model->openingQuantity / $fields->conversion_rate, 2);
                }

                if($model->quantity != 0){

                    $model->quantity = round($model->quantity / $fields->conversion_rate, 2);
                }

                if($model->alertQuantity != 0){

                    $model->alertQuantity = round($model->alertQuantity / $fields->conversion_rate, 2);
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
        return [
            Select::make("Unit")
                ->rules('required')
                ->options(Unit::all()->pluck('name', 'id')),

            Number::make('Conversion Rate')
                ->rules('required', 'numeric'),
        ];
    }
}
