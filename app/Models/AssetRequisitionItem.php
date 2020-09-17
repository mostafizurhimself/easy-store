<?php

namespace App\Models;

use App\Traits\CamelCasing;
use App\Enums\RequisitionStatus;
use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetRequisitionItem extends Model
{
    use LogsActivity, SoftDeletes, CamelCasing, HasReadableIdWithDate;

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
        return "ARI";
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
    public function requisition()
    {
       return $this->belongsTo(AssetRequisition::class, 'requisition_id')->withTrashed();
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
    public function distributionItems()
    {
       return $this->hasMany(AssetDistributionItem::class, 'requisition_item_id');
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
     * Get the remaining requisition quantity
     *
     * @return double
     */
    public function getRemainingQuantityAttribute()
    {
        return $this->requisitionQuantity - $this->distributionItems->sum('distribution_quantity');
    }

    /**
     * Update the distribution quantity
     *
     * @return void
     */
    public function updateDistributionQuantity()
    {
        $this->distributionQuantity = $this->distributionItems->sum('distribution_quantity');
        $this->save();
    }

    /**
     * Update the distribution amount
     *
     * @return void
     */
    public function updateDistributionAmount()
    {
        $this->distributionAmount = $this->distributionItems->sum('distribution_amount');
        $this->save();
    }

    /**
     * Update requisition item status
     *
     * @return void
     */
    public function updateStatus()
    {
        if($this->distributionItems()->exists() && ($this->requisitionQuantity == $this->distributionQuantity)){
            $this->status = RequisitionStatus::DISTRIBUTED();
        }

        if($this->distributionItems()->exists() && ($this->requisitionQuantity != $this->distributionQuantity)){
            $this->status = RequisitionStatus::PARTIAL();
        }

        if(!$this->distributionItems()->exists()){
            $this->status = RequisitionStatus::CONFIRMED();
        }

        $this->save();

    }

}
