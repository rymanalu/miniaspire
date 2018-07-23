<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    /**
     * Loan unpaid status.
     *
     * @var int
     */
    const STATUS_UNPAID = 0;

    /**
     * Loan paid status.
     *
     * @var int
     */
    const STATUS_PAID = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'duration', 'rate', 'amount', 'fee', 'total', 'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rate' => 'double',
        'amount' => 'double',
        'fee' => 'double',
        'total' => 'double',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'fee_per_week', 'weekly_repayment',
    ];

    /**
     * Get the user that owns the loan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the repayments for the loan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    /**
     * Get fee per week for the loan.
     *
     * @return double
     */
    public function getFeePerWeekAttribute()
    {
        return $this->fee / $this->duration;
    }

    /**
     * Get weekly repayment for the loan.
     *
     * @return double
     */
    public function getWeeklyRepaymentAttribute()
    {
        return $this->total / $this->duration;
    }
}
