<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasReadableIdWithDate;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetRequisition extends Model implements HasMedia
{
    use LogsActivity, SoftDeletes, InteractsWithMedia, HasReadableIdWithDate;

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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date', 'deadline'];

    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "AR";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 4;

    /**
     * Register the media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
       $this->addMediaCollection('requisition-attachments');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requisitionItems()
    {
       return $this->hasMany(AssetRequisitionItem::class, 'requisition_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver()
    {
       return $this->belongsTo(Location::class, 'receiver_id');
    }

    /**
     * Get the purchase assets ids as an array
     *
     * @return array
     */
    public function assetIds($id = null)
    {
        return $this->requisitionItems->whereNotIn('asset_id', [$id])->pluck('asset_id')->toArray();
    }


    /**
     * Update the total purchase amount
     *
     * @return void
     */
    public function updateRequisitionAmount()
    {
        $this->totalRequisitionAmount = $this->requisitionItems->sum('requisition_amount');
        $this->save();
    }

}
