<?php

namespace App\Http\Controllers\Api;

use App\Fee;
use App\Http\Controllers\Controller;

class FeesController extends Controller
{
    /**
     * Display a listing of the fees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->api(['fees' => Fee::all()]);
    }
}
