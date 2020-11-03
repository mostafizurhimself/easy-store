<?php

namespace App\Models;

use App\Facades\Settings;
use App\Enums\AddressType;
use App\Traits\CamelCasing;
use App\Traits\HasReadableId;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use LogsActivity, HasReadableId, CamelCasing, SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['launch_date'];

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
        return Settings::prefix()->location ?? 'LO';
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 3;

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
       return $this->hasMany(User::class);
    }

    /**
     * Determines a morph one relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function address()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function departments()
    {
       return $this->hasMany(Department::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
       return $this->hasMany(Section::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function floors()
    {
       return $this->hasMany(Floor::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function styles()
    {
       return $this->hasMany(Style::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function designations()
    {
       return $this->hasMany(Designation::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employees()
    {
       return $this->hasMany(Employee::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fabricCategories()
    {
       return $this->hasMany(FabricCategory::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fabrics()
    {
       return $this->hasMany(Fabric::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materialCategories()
    {
       return $this->hasMany(MaterialCategory::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materials()
    {
       return $this->hasMany(Material::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assetCategories()
    {
       return $this->hasMany(AssetCategory::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assets()
    {
       return $this->hasMany(Asset::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceCategories()
    {
       return $this->hasMany(ServiceCategory::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
       return $this->hasMany(Service::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productCategories()
    {
       return $this->hasMany(ProductCategory::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
       return $this->hasMany(Product::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expensers()
    {
       return $this->hasMany(Expenser::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenseCategories()
    {
       return $this->hasMany(ExpenseCategory::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assetRequisitions()
    {
       return $this->hasMany(AssetRequisition::class);
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

    /**
     * Get the filter options of locations
     *
     * @return array
     */
    public static function filterOptions()
    {
        // Cache::forget('nova-location-filter-options');
        return Cache::remember('nova-location-filter-options', 3600, function () {
            return self::pluck('name', 'id')->toArray();
        });
    }

    /**
     * Get the filter options of locations
     *
     * @return array
     */
    public static function belongsToFilterOptions()
    {
        // Cache::forget('nova-location-belongs-to-filter-options');
        return Cache::remember('nova-location-belongs-to-filter-options', 3600, function () {
            return self::pluck('id', 'name')->toArray();
        });
    }

}
