<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    /**
     * Repayment unpaid status.
     *
     * @var int
     */
    const STATUS_UNPAID = 0;

    /**
     * Repayment paid status.
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
        'loan_id', 'week', 'amount', 'fee', 'total', 'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'double',
        'fee' => 'double',
        'total' => 'double',
    ];

    /**
     * Determine if the repayment is already paid.
     *
     * @return bool
     */
    public function alreadyPaid()
    {
        return $this->status == static::STATUS_PAID;
    }

    /**
     * Get the loan of the repayment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
