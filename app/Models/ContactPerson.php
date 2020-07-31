<?php

namespace App\Models;

use App\Traits\CamelCasing;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactPerson extends Model
{
    use LogsActivity, SoftDeletes, CamelCasing;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "contact_people";

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Add all attributes that are not listed in $guarded for log
     *
     * @var boolean
     */
    protected static $logUnguarded = true;

    /**
     * Get the owning contactable model.
     */
    public function contactable()
    {
        return $this->morphTo();
    }

}
