<?php

namespace App\Models;

use App\Enums\TransferStatus;
use App\Traits\HasReadableIdWithDate;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceTransferInvoice extends Model
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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['location'];


    /**
     * Set teh models readable prefix
     *
     * @return string
     */
    public static function readableIdPrefix()
    {
        return "ST";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 4;

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferItems()
    {
        return $this->hasMany(ServiceTransferItem::class, 'invoice_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receiveItems()
    {
        return $this->hasMany(ServiceTransferReceiveItem::class, 'invoice_id');
    }

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
     * Get the purchase service ids as an array
     *
     * @return array
     */
    public function serviceIds($id = null)
    {
        return $this->transferItems->whereNotIn('service_id', [$id])->pluck('service_id')->toArray();
    }

    /**
     * Update the total dispatch amount
     *
     * @return void
     */
    public function updateTransferAmount()
    {
        $this->totalTransferAmount = $this->transferItems->sum('transfer_amount');
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
        $status = $this->transferItems()->pluck('status')->unique();
        if ($status->count() == 1  && $status->first() == TransferStatus::CONFIRMED()) {
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
        $status = $this->transferItems()->pluck('status')->unique();
        if ($status->count() == 1  && $status->first() == TransferStatus::RECEIVED()) {
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
        if ($this->receiveItems()->exists()) {
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
        if ($this->transferItems()->exists()) {

            if ($this->isConfirmed()) {
                $this->status = TransferStatus::CONFIRMED();
                $this->save();
                return;
            }

            if ($this->isReceived()) {
                $this->status = TransferStatus::RECEIVED();
                $this->save();
                return;
            }

            if ($this->isPartial()) {
                $this->status = TransferStatus::PARTIAL();
                $this->save();
                return;
            }
        } else {
            $this->status = TransferStatus::DRAFT();
            $this->save();
        }
    }
}