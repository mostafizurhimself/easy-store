<?php

namespace App\Models;

use App\Enums\ReturnStatus;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasReadableIdWithDate;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialReturnInvoice extends Model implements HasMedia
{
    use LogsActivity, HasReadableIdWithDate, SoftDeletes, InteractsWithMedia;

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
    protected $with = ['supplier', 'location'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date'];

    /**
     * Register the media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('return-invoice-attachments');
    }

    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "MR";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 4;

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
    public function returnItems()
    {
        return $this->hasMany(MaterialReturnItem::class, 'invoice_id');
    }

    /**
     * Get the purchase materials ids as an array
     *
     * @return array
     */
    public function materialIds($id = null)
    {
        return $this->returnItems->whereNotIn('material_id', [$id])->pluck('material_id')->toArray();
    }

    /**
     * Update the total receive amount
     *
     * @return void
     */
    public function updateReturnAmount()
    {
        $this->totalReturnAmount = $this->returnItems->sum('amount');
        $this->save();
    }

    /**
     * Check all the purchase items status is confirmed or not
     *
     * @return bool
     */
    public function isConfirmed()
    {
        $status = $this->receiveItems()->pluck('status')->unique();
        if ($status->count() == 1  && $status->first() == ReturnStatus::CONFIRMED()) {
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
        if ($this->receiveItems()->exists()) {

            if ($this->isConfirmed()) {
                $this->status = ReturnStatus::CONFIRMED();
                $this->save();
                return;
            }
        } else {
            $this->status = ReturnStatus::DRAFT();
            $this->save();
        }
    }
}