<?php

namespace App\Nova\Actions;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Textarea;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdjustBalance extends Action
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

            $adjustBalance = $model->adjustBalances()->create([
                'date'        => Carbon::now(),
                'amount'      => $fields->amount,
                'description' => $fields->description,
                'user_id'     => request()->user()->id,
            ]);

            $adjustBalance->adjust();
        }

        Action::message('Balance adjusted successfully.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Currency::make('Amount', 'amount')
                ->rules('required', 'numeric'),

            Textarea::make('Description', 'description')
                ->rules('required', 'max:500')
        ];
    }
}
