<?php

namespace App\Models;

use App\Traits\CamelCasing;
use App\Enums\DistributionStatus;
use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetDistributionItem extends Model
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
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "ADI";
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
    public function invoice()
    {
       return $this->belongsTo(AssetDistributionInvoice::class, 'invoice_id')->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requisition()
    {
       return $this->belongsTo(AssetRequisition::class, 'requisition_id')->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requisitionItem()
    {
       return $this->belongsTo(AssetRequisitionItem::class, 'requisition_item_id')->withTrashed();
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receiveItems()
    {
       return $this->hasMany(AssetDistributionReceiveItem::class, 'distribution_item_id');
    }

    /**
     * Get the purchase item remaining receive quantity
     *
     * @return double
     */
    public function getRemainingQuantityAttribute()
    {
        return $this->distributionQuantity - $this->receiveQuantity;
    }


    /**
     * Scope a query to only include draft distributions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', DistributionStatus::DRAFT());
    }

    /**
     * Get the unit for the assets
     *
     * @return string
     */
    public function getUnitAttribute()
    {
        return $this->asset->unit->name;
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
        if($this->receiveItems()->exists() && ($this->distributionQuantity == $this->receiveQuantity)){
            $this->status = DistributionStatus::RECEIVED();
        }

        if($this->receiveItems()->exists() && ($this->distributionQuantity != $this->receiveQuantity)){
            $this->status = DistributionStatus::PARTIAL();
        }

        if(!$this->receiveItems()->exists()){
            $this->status = DistributionStatus::CONFIRMED();
        }

        $this->save();

    }


}
