<?php

namespace App\Http\Controllers\Api;

use App\Fee;
use App\Loan;
use App\Repayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoanRequest;

class LoansController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Create a new loan.
     *
     * @param  \App\Http\Requests\Api\LoanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function create(LoanRequest $request)
    {
        $loan = DB::transaction(function () use ($request) {
            $loanData = $this->getLoanDataFromRequest($request);

            $loan = Loan::create($loanData);

            $this->createRepayments($loan);

            return $loan;
        });

        if ($loan) {
            return response()->api([
                'loan' => $loan,
                'meta_message' => 'New loan has been created',
            ], 201);
        }

        return response()->api(['meta_message' => 'Failed to create a new loan'], 500);
    }

    /**
     * Create repayment for the given loan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function repay(Request $request, Loan $loan)
    {
        if (! $loan->ownedBy($request->user())) {
            return response()->api(['meta_message' => 'Invalid given loan'], 422);
        }

        if ($loan->alreadyPaid()) {
            return response()->api(['meta_message' => 'Loan is already paid'], 422);
        }

        $repayment = $loan->getCurrentRepayment();
        $repayment->status = Repayment::STATUS_PAID;
        $paymentSucceed = $repayment->save();

        if ($paymentSucceed) {
            return response()->api(['meta_message' => 'Repayment Week #'.$repayment->week.' succeed']);
        }

        return response()->api(['meta_message' => 'Failed to paid'], 500);
    }

    /**
     * Prepare loan data from request.
     *
     * @param  \App\Http\Requests\Api\LoanRequest  $request
     * @return array
     */
    protected function getLoanDataFromRequest(LoanRequest $request)
    {
        $fee = Fee::find($request->fee_id);

        $data = $request->only('amount');
        $data['user_id'] = $request->user()->id;
        $data['duration'] = $fee->weeks;
        $data['rate'] = $fee->rate;
        $data['fee'] = ($data['amount'] * $data['rate']) / 100;
        $data['total'] = $data['amount'] + $data['fee'];
        $data['status'] = Loan::STATUS_UNPAID;

        return $data;
    }

    /**
     * Create repayments data for given loan.
     *
     * @param  \App\Loan  $loan
     * @return void
     */
    protected function createRepayments(Loan $loan)
    {
        for ($i = 1; $i <= $loan->duration; $i++) {
            $loan->repayments()->create([
                'week' => $i,
                'amount' => $loan->amount / $loan->duration,
                'fee' => $loan->fee / $loan->duration,
                'total' => $loan->total / $loan->duration,
                'status' => Repayment::STATUS_UNPAID,
            ]);
        }
    }
}
