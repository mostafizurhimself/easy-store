<?php

namespace App\Nova\Actions\Attendances;

use Michielfb\Time\Time;
use App\Enums\ConfirmStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laraning\NovaTimeField\TimeField;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckOut extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The number of models that should be included in each chunk.
     *
     * @var int
     */
    public static $chunkCount = 200000000;

    /**
     * Disables action log events for this action.
     *
     * @var bool
     */
    public $withoutActionEvents = true;

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
            if ($model->status == ConfirmStatus::CONFIRMED() && empty($model->out)) {
                $model->out = $fields->out;
                $model->save();
            }else{
                return Action::danger('Can not check out now.');
            }
        }

        return Action::message('Checkout successfully.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            TimeField::make('Out', 'out')
                ->required(),
        ];
    }
}
