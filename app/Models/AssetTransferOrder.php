<?php

namespace App\Models;

use App\Traits\HasReadableIdWithDate;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetTransferOrder extends Model
{
    use LogsActivity, HasReadableIdWithDate;

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
       return $this->belongsTo(Location::class);
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
