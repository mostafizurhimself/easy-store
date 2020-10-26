<?php
namespace App\Traits;

use App\Enums\PurchaseStatus;
use Illuminate\Database\Eloquent\Builder;

trait BilledScope
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootBilledScope()
    {
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', '=', PurchaseStatus::BILLED());
        });
    }
}

