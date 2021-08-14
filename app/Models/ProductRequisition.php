<?php

namespace App\Models;

use App\Traits\HasDate;
use App\Enums\RequisitionStatus;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasReadableIdWithDate;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRequisition extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasReadableIdWithDate, HasDate;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['location'];

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
        return "PR";
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
        return $this->hasMany(ProductRequisitionItem::class, 'requisition_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function distributionItems()
    // {
    //    return $this->hasMany(AssetDistributionItem::class, 'requisition_id');
    // }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver()
    {
        return $this->belongsTo(Location::class, 'receiver_id')->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function distributions()
    // {
    //    return $this->hasMany(AssetDistributionInvoice::class, 'requisition_id');
    // }

    /**
     * Get the requisition products ids as an array
     *
     * @return array
     */
    public function productIds($id = null)
    {
        return $this->requisitionItems->whereNotIn('product_id', [$id])->pluck('product_id')->toArray();
    }


    /**
     * Update the total requisition amount
     *
     * @return void
     */
    public function updateRequisitionAmount()
    {
        $this->totalRequisitionAmount = $this->requisitionItems->sum('requisition_amount');
        $this->save();
    }

    /**
     * Update the total distribution amount
     *
     * @return void
     */
    // public function updateDistributionAmount()
    // {
    //     $this->totalDistributionAmount = $this->requisitionItems->sum('distribution_amount');
    //     $this->save();
    // }

    /**
     * Check all the requisition items status is confirmed or not
     *
     * @return bool
     */
    public function isConfirmed()
    {
        $status = $this->requisitionItems()->pluck('status')->unique();
        if ($status->count() == 1  && $status->first() == RequisitionStatus::CONFIRMED()) {
            return true;
        }
        return false;
    }

    /**
     * Check all the requisition items status is distributed or not
     *
     * @return bool
     */
    // public function isDistributed()
    // {
    //     $status = $this->requisitionItems()->pluck('status')->unique();
    //     if($status->count() == 1  && $status->first() == RequisitionStatus::DISTRIBUTED()){
    //         return true;
    //     }
    //     return false;
    // }

    /**
     * Check any of the requisition items status is partial or not
     *
     * @return bool
     */
    // public function isPartial()
    // {
    //     if($this->requisitionItems()->exists()){
    //         return true;
    //     }
    //     return false;
    // }

    /**
     * Update the requisition status
     *
     * @return void
     */
    public function updateStatus()
    {
        if ($this->requisitionItems()->exists()) {

            if ($this->isConfirmed()) {
                $this->status = RequisitionStatus::CONFIRMED();
                $this->save();
                return;
            }

            // if($this->isDistributed()){
            //     $this->status = RequisitionStatus::DISTRIBUTED();
            //     $this->save();
            //     return;
            // }

            // if($this->isPartial()){
            //     $this->status = RequisitionStatus::PARTIAL();
            //     $this->save();
            //     return;
            // }

        } else {
            $this->status = RequisitionStatus::DRAFT();
            $this->save();
        }
    }
}