<?php

namespace App\Models;

use App\Facades\Settings;
use App\Traits\CamelCasing;
use App\Traits\HasReadableId;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Location extends Model
{
    use LogsActivity, HasReadableId, CamelCasing;

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
        return Settings::prefix()->location ?? 'LO';
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 3;

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
    public function floors()
    {
       return $this->hasMany(Floor::class);
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
    public function fabricCategories()
    {
       return $this->hasMany(FabricCategory::class);
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
    public function assetCategories()
    {
       return $this->hasMany(AssetCategory::class);
    }
}
