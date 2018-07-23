<?php

namespace App\Observers;

use App\Loan;
use App\Repayment;

class RepaymentObserver
{
    /**
     * Handle to the repayment "created" event.
     *
     * @param  \App\Repayment  $repayment
     * @return void
     */
    public function created(Repayment $repayment)
    {
        //
    }

    /**
     * Handle the repayment "updated" event.
     *
     * @param  \App\Repayment  $repayment
     * @return void
     */
    public function updated(Repayment $repayment)
    {
        $loan = $repayment->loan;

        if ($repayment->week == $loan->duration) {
            $loan->status = Loan::STATUS_PAID;
            $loan->save();
        }
    }

    /**
     * Handle the repayment "deleted" event.
     *
     * @param  \App\Repayment  $repayment
     * @return void
     */
    public function deleted(Repayment $repayment)
    {
        //
    }
}
