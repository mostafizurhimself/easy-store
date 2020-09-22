<?php
namespace App\Traits;

use App\Models\Location;
use Illuminate\Support\Facades\Auth;
trait Locationable
{
    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
       return $this->belongsTo(Location::class)->withTrashed();
    }

    /**
     * Set the location id of the model
     */
    public static function bootLocationable()
    {
        static::creating(function($model){
            if(Auth::check() && empty($model->locationId)){
                $model->locationId = Auth::user()->locationId;
            }
        });
    }

}
