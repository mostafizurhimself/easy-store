<?php

namespace App\Models;

use App\Enums\DispatchStatus;
use App\Traits\HasReadableIdWithDate;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceInvoice extends Model
{
    use LogsActivity, HasReadableIdWithDate, SoftDeletes;

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
    protected $dates = ['date'];


    /**
     * Set teh models readable prefix
     *
     * @return string
     */
    public static function readableIdPrefix()
    {
        return "SI";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 4;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['totalDispatchQuantity', 'totalReceiveQuantity', 'totalRemainingQuantity'];

    /**
     * Get the model goodsGatePass
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function goodsGatePass()
    {
        return $this->morphOne(GoodsGatePass::class, 'invoice');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dispatches()
    {
        return $this->hasMany(ServiceDispatch::class, 'invoice_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receives()
    {
        return $this->hasMany(ServiceReceive::class, 'invoice_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class)->withTrashed();
    }

    /**
     * Get the purchase service ids as an array
     *
     * @return array
     */
    public function serviceIds($id = null)
    {
        return $this->dispatches->whereNotIn('service_id', [$id])->pluck('service_id')->toArray();
    }

    /**
     * Update the total dispatch amount
     *
     * @return void
     */
    public function updateDispatchAmount()
    {
        $this->totalDispatchAmount = $this->dispatches->sum('dispatch_amount');
        $this->save();
    }

    /**
     * Update the total receive amount
     *
     * @return void
     */
    public function updateReceiveAmount()
    {
        $this->totalReceiveAmount = $this->receives->sum('amount');
        $this->save();
    }

    /**
     * Check all the purchase items status is confirmed or not
     *
     * @return bool
     */
    public function isConfirmed()
    {
        $status = $this->dispatches()->pluck('status')->unique();
        if ($status->count() == 1  && $status->first() == DispatchStatus::CONFIRMED()) {
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
        $status = $this->dispatches()->pluck('status')->unique();
        if ($status->count() == 1  && $status->first() == DispatchStatus::RECEIVED()) {
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
        if ($this->receives()->exists()) {
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
        if ($this->dispatches()->exists()) {

            if ($this->isConfirmed()) {
                $this->status = DispatchStatus::CONFIRMED();
                $this->save();
                return;
            }

            if ($this->isReceived()) {
                $this->status = DispatchStatus::RECEIVED();
                $this->save();
                return;
            }

            if ($this->isPartial()) {
                $this->status = DispatchStatus::PARTIAL();
                $this->save();
                return;
            }
        } else {
            $this->status = DispatchStatus::DRAFT();
            $this->save();
        }
    }

    /**
     * Get total dispatch quantity
     * 
     * @return double
     */
    public function getTotalDispatchQuantityAttribute()
    {
        return $this->dispatches()->sum('dispatch_quantity');
    }

    /**
     * Get total receive quantity
     * 
     * @return double
     */
    public function getTotalReceiveQuantityAttribute()
    {
        return $this->receives()->sum('quantity');
    }

    /**
     * Get total remaining quantity
     * 
     * @return double
     */
    public function getTotalRemainingQuantityAttribute()
    {
        return $this->totalDispatchQuantity - $this->totalReceiveQuantity;
    }
}