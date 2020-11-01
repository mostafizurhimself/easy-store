<?php

namespace App\Nova\Actions;

use Carbon\Carbon;
use App\Enums\AdjustType;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\Textarea;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdjustQuantity extends Action
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
            // Set the rate property.
            $model->rate = $model->rate ?? $model->costPrice;

            $adjustQuantity = $model->adjustQuantities()->create([
                'date'        => Carbon::now(),
                'type'        => $fields->type,
                'quantity'    => $fields->quantity,
                'rate'        => $model->rate,
                'amount'      => $model->rate * $fields->quantity,
                'description' => $fields->description,
                'unit_id'     => $model->unit_id,
                'user_id'     => request()->user()->id,
            ]);

            $adjustQuantity->adjust();
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
            Select::make('Adjust Type', 'type')
                ->rules('required')
                ->options(AdjustType::titleCaseOptions()),

            Number::make('Quantity', 'quantity')
                ->rules('required', 'numeric', 'min:0'),

            Textarea::make('Description', 'description')
                ->rules('nullable', 'max:500')
        ];
    }
}
