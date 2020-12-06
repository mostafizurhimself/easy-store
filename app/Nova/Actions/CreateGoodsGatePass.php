<?php

namespace App\Nova\Actions;

use App\Enums\GatePassStatus;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Actions\Action;
use App\Enums\DistributionStatus;
use Laravel\Nova\Fields\Textarea;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateGoodsGatePass extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Create/Update Gate Pass';

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
            if ($model->status == DistributionStatus::CONFIRMED()) {
                if (!$model->goodsGatePass()->exists()) {
                    $model->goodsGatePass()->create([
                        'details->total_ctn'  => $fields->total_ctn,
                        'details->total_poly' => $fields->total_poly,
                        'details->total_bag'  => $fields->total_bag,
                        'note'                => $fields->note
                    ]);
                } elseif ($model->goodsGatePass()->exists() && $model->goodsGatePass->status == GatePassStatus::DRAFT()) {
                    $model->goodsGatePass()->update([
                        'details->total_ctn'  => $fields->total_ctn ?? 0,
                        'details->total_poly' => $fields->total_poly ?? 0,
                        'details->total_bag'  => $fields->total_bag ?? 0,
                        'note'                => $fields->note ?? $model->goodsGatePass->note,
                    ]);
                } else {
                    return Action::danger('Can not update gate pass now.');
                }
            } else {
                return Action::danger('Can not create gate pass now.');
            }
        }

        return Action::message('Gate pass created successfully');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Number::make('Total CTN', 'total_ctn')
                ->rules('nullable', 'numeric', 'min:0')
                ->default(0),

            Number::make('Total Poly', 'total_poly')
                ->rules('nullable', 'numeric', 'min:0')
                ->default(0),

            Number::make('Total Bag', 'total_bag')
                ->rules('nullable', 'numeric', 'min:0')
                ->default(0),

            Textarea::make('Note')
                ->rules('nullable', 'max:500'),
        ];
    }
}
