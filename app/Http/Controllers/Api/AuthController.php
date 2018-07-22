<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only('logout');
    }

    /**
     * Register a new user.
     *
     * @param  \App\Http\Requests\Api\RegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $user = DB::transaction(function () use ($request) {
            $data = $request->only('name', 'email');
            $data['password'] = bcrypt($request->password);

            return User::create($data);
        });

        if ($user) {
            return response()->api([
                'user' => $user,
                'meta_message' => 'Register succeed',
            ], 201);
        }

        return response()->api(['meta_message' => 'Register failed'], 500);
    }

    /**
     * Logging in a user.
     *
     * @param  \App\Http\Requests\Api\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->api(['meta_message' => 'Unauthorized'], 401);
        }

        $user = $request->user();

        $token = $user->createToken('Personal Access Token')->accessToken;

        return response()->api([
            'user' => $user,
            'access_token' => $token,
            'meta_message' => 'Authorized',
        ]);
    }

    /**
     * Logging out a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->api(['meta_message' => 'Logged out']);
    }
}
