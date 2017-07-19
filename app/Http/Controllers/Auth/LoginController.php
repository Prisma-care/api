<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class LoginController extends Controller
{

    public function signin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                $response = response()->json([
                    'code' => '401',
                    'message' => 'Invalid credentials'
                ], 401);
            } else {
                $response = response()->json([
                    'meta' => [
                        'code' => '200',
                        'message' => 'OK'
                    ],
                    'token' => $token
                ], 200);
            }
        } catch (JWTException $e) {
            $response = response()->json([
                'code' => '500',
                'message' => 'Unexpected error logging in the user'
            ], 500);
        }

        return $response;
    }
}
