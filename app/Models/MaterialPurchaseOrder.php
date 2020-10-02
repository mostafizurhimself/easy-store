<?php

namespace App\Models;

use App\Enums\PurchaseStatus;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasReadableIdWithDate;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialPurchaseOrder extends Model implements HasMedia
{
    use LogsActivity, HasReadableIdWithDate, SoftDeletes, InteractsWithMedia;

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
    protected $with = ['supplier'];

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
       $this->addMediaCollection('purchase-order-attachments');
    }

    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "POM";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 5;

    /**
     * Get the model approve
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function approve()
    {
        return $this->morphOne(Approve::class, 'approvable');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
       return $this->belongsTo(Supplier::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseItems()
    {
       return $this->hasMany(MaterialPurchaseItem::class, 'purchase_order_id', 'id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receiveItems()
    {
       return $this->hasMany(MaterialReceiveItem::class, 'purchase_order_id');
    }

    /**
     * Get the purchase fabrics ids as an array
     *
     * @return array
     */
    public function materialIds($id = null)
    {
        return $this->purchaseItems->whereNotIn('material_id', [$id])->pluck('material_id')->toArray();
    }

    /**
     * Update the total purchase amount
     *
     * @return void
     */
    public function updatePurchaseAmount()
    {
        $this->totalPurchaseAmount = $this->purchaseItems->sum('purchase_amount');
        $this->save();
    }

    /**
     * Update the total receive amount
     *
     * @return void
     */
    public function updateReceiveAmount()
    {
        $this->totalReceiveAmount = $this->receiveItems->sum('amount');
        $this->save();
    }

    /**
     * Check all the purchase items status is confirmed or not
     *
     * @return bool
     */
    public function isConfirmed()
    {
        $status = $this->purchaseItems()->pluck('status')->unique();
        if($status->count() == 1  && $status->first() == PurchaseStatus::CONFIRMED()){
            return true;
        }
        return false;
    }

    /**
     * Check all the purchase items status is received or not
     *
     * @return bool
     */
    public function isReceived()
    {
        $status = $this->purchaseItems()->pluck('status')->unique();
        if($status->count() == 1  && $status->first() == PurchaseStatus::RECEIVED()){
            return true;
        }
        return false;
    }

    /**
     * Check any of the purchase items status is partial or not
     *
     * @return bool
     */
    public function isPartial()
    {
        if($this->receiveItems()->exists()){
            return true;
        }
        return false;
    }

    /**
     * Update the purchase status
     *
     * @return void
     */
    public function updateStatus()
    {
        if($this->purchaseItems()->exists()){

            if($this->isConfirmed()){
                $this->status = PurchaseStatus::CONFIRMED();
                $this->save();
                return;
            }

            if($this->isReceived()){
                $this->status = PurchaseStatus::RECEIVED();
                $this->save();
                return;
            }

            if($this->isPartial()){
                $this->status = PurchaseStatus::PARTIAL();
                $this->save();
                return;
            }

        }else{
            $this->status = PurchaseStatus::DRAFT();
            $this->save();
        }
    }

    /**
     * Get the approver details of the purchase order
     *
     * @return \App\Models\Employee
     */
    public function getApproverAttribute()
    {
        return $this->approve->employee;
    }
}
