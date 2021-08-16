<?php

namespace App\Models;

use App\Facades\Settings;
use App\Enums\AddressType;
use App\Traits\CamelCasing;
use App\Traits\HasReadableId;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use SoftDeletes, HasReadableId, CamelCasing;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_provider')->withTrashed();
    }

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
        if ($this->address()->exists()) {

            return $this->address->where('type', AddressType::LOCATION_ADDRESS())->first();
        }

        return null;
    }

    /**
     * Get the filter options of providers
     *
     * @return array
     */
    public static function belongsToFilterOptions()
    {
        // Cache::forget('nova-providers-belongs-to-filter-options');
        return Cache::remember('nova-providers-belongs-to-filter-options', 3600, function () {
            return self::orderBy('name')->pluck('id', 'name')->toArray();
        });
    }
}