<?php
namespace App\Traits;

use App\Enums\ActiveStatus;
use Illuminate\Database\Eloquent\Builder;

trait ActiveScope
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootActiveScope()
    {
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', '=', ActiveStatus::ACTIVE());
        });
    }
}

