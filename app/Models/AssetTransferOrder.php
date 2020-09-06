<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasReadableIdWithDate;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetTransferOrder extends Model implements HasMedia
{
    use LogsActivity, HasReadableIdWithDate, SoftDeletes, InteractsWithMedia;

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
    protected $dates = ['date'];

    /**
     * Add all attributes that are not listed in $guarded for log
     *
     * @var boolean
     */
    protected static $logUnguarded = true;

    /**
     * Register the media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
       $this->addMediaCollection('transfer-order-attachments')->singleFile();
    }

    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "TOA";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 5;

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver()
    {
       return $this->belongsTo(Location::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferItems()
    {
       return $this->hasMany(AssetTransferItem::class, 'transfer_order_id');
    }

    /**
     * Get the purchase assets ids as an array
     *
     * @return array
     */
    public function assetIds($id = null)
    {
        return $this->transferItems->whereNotIn('asset_id', [$id])->pluck('asset_id')->toArray();
    }

    /**
     * Update the total purchase amount
     *
     * @return void
     */
    public function updateTotalAmount()
    {
        $this->totalAmount = $this->transferItems->sum('amount');
        $this->save();
    }

}
