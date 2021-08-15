<?php

namespace App\Models;

use App\Traits\HasReadableIdWithDate;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinishingInvoice extends Model
{
    use LogsActivity, SoftDeletes, HasReadableIdWithDate;

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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['location'];


    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "FI";
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
    public function finishings()
    {
        return $this->hasMany(Finishing::class, 'invoice_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function floor()
    {
        return $this->belongsTo(Floor::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Section::class)->withTrashed();
    }

    /**
     * Get the purchase product ids as an array
     *
     * @return array
     */
    public function productIds($id = null)
    {
        return $this->finishings->whereNotIn('product_id', [$id])->pluck('product_id')->toArray();
    }

    /**
     * Get the purchase style ids as an array
     *
     * @return array
     */
    public function styleIds($id = null)
    {
        return $this->finishings->whereNotIn('style_id', [$id])->pluck('style_id')->toArray();
    }

    /**
     * Update the total purchase amount
     *
     * @return void
     */
    public function updateTotalAmount()
    {
        $this->totalAmount = $this->finishings->sum('amount');
        $this->save();
    }

    /**
     * Update the total receive amount
     *
     * @return void
     */
    public function updateTotalQuantity()
    {
        $this->totalQuantity = $this->finishings->sum('quantity');
        $this->save();
    }
}