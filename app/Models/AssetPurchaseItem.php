<?php

namespace App\Models;

use App\Traits\CamelCasing;
use App\Enums\PurchaseStatus;
use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetPurchaseItem extends Model
{
    use LogsActivity, CamelCasing, SoftDeletes, HasReadableIdWithDate;

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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['date', 'unitName'];

    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "PIA";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 6;

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(AssetPurchaseOrder::class, 'purchase_order_id')->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class)->withTrashed();
    }

    /**
     * Get the unit for the model
     *
     * @return string
     */
    public function getUnitNameAttribute()
    {
        return $this->unit->name;
    }

    /**
     * Get the date of the purchase order
     *
     * @return string
     */
    public function getDateAttribute()
    {
        return $this->purchaseOrder->date->format('Y-m-d');
    }

    /**
     * Get the location of the model
     *
     * @return string
     */
    public function getLocationAttribute()
    {
        return $this->purchaseOrder->location;
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receiveItems()
    {
        return $this->hasMany(AssetReceiveItem::class, 'purchase_item_id');
    }

    /**
     * Get the purchase item remaining receive quantity
     *
     * @return double
     */
    public function getRemainingQuantityAttribute()
    {
        return $this->purchaseQuantity - $this->receiveQuantity;
    }

    /**
     * Update total receive quantity
     *
     * @return void
     */
    public function updateReceiveQuantity()
    {
        $this->receiveQuantity = $this->receiveItems->sum('quantity');
        $this->save();
    }

    /**
     * Update total receive amount
     *
     * @return void
     */
    public function updateReceiveAmount()
    {
        $this->receiveAmount = $this->receiveItems->sum('amount');
        $this->save();
    }

    /**
     * Update purchase item status
     *
     * @return void
     */
    public function updateStatus()
    {
        if ($this->receiveItems()->exists() && ($this->purchaseQuantity == $this->receiveQuantity)) {
            $this->status = PurchaseStatus::RECEIVED();
        }

        if ($this->receiveItems()->exists() && ($this->purchaseQuantity != $this->receiveQuantity)) {
            $this->status = PurchaseStatus::PARTIAL();
        }

        if (!$this->receiveItems()->exists()) {
            $this->status = PurchaseStatus::CONFIRMED();
        }

        $this->save();
    }
}
