<?php

namespace App\Models;

use App\Traits\CamelCasing;
use App\Enums\RequisitionStatus;
use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRequisitionItem extends Model
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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['product'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['unit'];


    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "PRI";
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
        return $this->belongsTo(ProductRequisition::class, 'requisition_id')->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function distributionItems()
    // {
    //    return $this->hasMany(AssetDistributionItem::class, 'requisition_item_id');
    // }

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


    // /**
    //  * Get the remaining requisition quantity
    //  *
    //  * @return double
    //  */
    // public function getRemainingQuantityAttribute()
    // {
    //     return $this->requisitionQuantity - $this->distributionItems->sum('distribution_quantity');
    // }

    // /**
    //  * Update the distribution quantity
    //  *
    //  * @return void
    //  */
    // public function updateDistributionQuantity()
    // {
    //     $this->distributionQuantity = $this->distributionItems->sum('distribution_quantity');
    //     $this->save();
    // }

    // /**
    //  * Update the distribution amount
    //  *
    //  * @return void
    //  */
    // public function updateDistributionAmount()
    // {
    //     $this->distributionAmount = $this->distributionItems->sum('distribution_amount');
    //     $this->save();
    // }

    /**
     * Update requisition item status
     *
     * @return void
     */
    // public function updateStatus()
    // {
    //     if($this->distributionItems()->exists() && ($this->requisitionQuantity == $this->distributionQuantity)){
    //         $this->status = RequisitionStatus::DISTRIBUTED();
    //     }

    //     if($this->distributionItems()->exists() && ($this->requisitionQuantity != $this->distributionQuantity)){
    //         $this->status = RequisitionStatus::PARTIAL();
    //     }

    //     if(!$this->distributionItems()->exists()){
    //         $this->status = RequisitionStatus::CONFIRMED();
    //     }

    //     $this->save();

    // }

}
