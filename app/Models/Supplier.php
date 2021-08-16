<?php

namespace App\Models;

use App\Facades\Settings;
use App\Enums\AddressType;
use App\Traits\ActiveScope;
use App\Traits\CamelCasing;
use App\Traits\HasReadableId;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes, HasReadableId, CamelCasing, ActiveScope;

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
        return Settings::prefix()->supplier ?? 'SP';
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 5;

    /**
     * Get the supplier address
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
        return $this->morphOne(ContactPerson::class, 'contactable', 'contactable_type', 'contactable_id');
    }

    /**
     * Determines many to many relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assets()
    {
        return $this->belongsToMany(Asset::class)->withTrashed();
    }

    /**
     * Determines many to many relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fabrics()
    {
        return $this->belongsToMany(Fabric::class)->withTrashed();
    }

    /**
     * Determines many to many relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function materials()
    {
        return $this->belongsToMany(Material::class)->withTrashed();
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
     * Get the filter options of suppliers
     *
     * @return array
     */
    public static function belongsToFilterOptions()
    {
        // Cache::forget('nova-supplier-belongs-to-filter-options');
        return Cache::remember('nova-supplier-belongs-to-filter-options', 3600, function () {
            return self::orderBy('name')->pluck('id', 'name')->toArray();
        });
    }
}