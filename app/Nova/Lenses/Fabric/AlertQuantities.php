<?php

namespace App\Nova\Lenses\Fabric;

use App\Enums\ActiveStatus;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;
use Treestoneit\TextWrap\TextWrap;
use Easystore\TextUppercase\TextUppercase;
use Laravel\Nova\Http\Requests\LensRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;

class AlertQuantities extends Lens
{
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->where('alert_quantity', '>=', 'quantity')
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable()->onlyOnIndex(),

            BelongsTo::make('Location')
                ->searchable()
                ->sortable()
                ->canSee(function ($request) {
                    if ($request->user()->hasPermissionTo('view any locations data') || $request->user()->isSuperAdmin()) {
                        return true;
                    }
                    return false;
                }),

            TextWrap::make('Name')
                ->onlyOnIndex()
                ->sortable()
                ->wrapMethod('length', 30),

            TextUppercase::make('Code')
                ->sortable(),

            Currency::make('Rate')
                ->currency('BDT')
                ->sortable()
                ->rules('required', 'numeric', 'min:0'),

            Text::make('Quantity')
                ->displayUsing(function () {
                    return $this->quantity . " " . $this->unit->name;
                })
                ->sortable()
                ->exceptOnForms(),

            BelongsTo::make('Category', 'category', 'App\Nova\FabricCategory')
                ->sortable(),

            Badge::make('Status')->map([
                ActiveStatus::ACTIVE()->getValue()   => 'success',
                ActiveStatus::INACTIVE()->getValue() => 'danger',
            ])
                ->sortable()
                ->label(function () {
                    return Str::title(Str::of($this->status)->replace('_', " "));
                }),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'fabric-alert-quantities';
    }
}
