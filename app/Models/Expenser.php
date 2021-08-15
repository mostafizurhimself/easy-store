<?php

namespace App\Models;

use App\Enums\ExpenseStatus;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expenser extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

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
     * Register the media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('expenser-images')->singleFile();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    /**
     * Get the model adjust balances
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function adjustBalances()
    {
        return $this->morphMany(AdjustBalance::class, 'adjustable');
    }

    /**
     * Get the balance attribute of the expenser
     *
     * @return double
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->balance - $this->expenses->where('status', ExpenseStatus::DRAFT())->sum('amount');
    }

    /**
     * Determines one-to-one relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}