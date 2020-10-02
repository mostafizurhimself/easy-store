<?php

namespace App\Models;

use App\Facades\Settings;
use App\Enums\AddressType;
use App\Traits\CamelCasing;
use App\Traits\HasReadableId;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use LogsActivity, SoftDeletes, HasReadableId, CamelCasing;

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
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return Settings::prefix()->provider ?? 'PVD';
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 5;

    /**
     * Get the provider address
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function address()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get the contact person
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function contactPerson()
    {
        return $this->morphMany(ContactPerson::class, 'contactable', 'contactable_type', 'contactable_id');
    }

    /**
     * Get the location address of the location
     *
     * @return \App\Models\Address
     */
    public function getLocationAddressAttribute()
    {
        if($this->address()->exists()){

            return $this->address->where('type', AddressType::LOCATION_ADDRESS())->first();
        }

        return null;
    }

}
