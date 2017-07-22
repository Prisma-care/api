<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use App\Exceptions\JsonException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

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
                throw new JsonException('Invalid credentials', 401);
            } else {
                $response = response()->json([
                    'meta' => [
                        'code' => '200',
                        'message' => 'OK'
                    ],
                    'response' => [
                        'token' => $token
                    ]
                ], 200);
            }
        } catch (JWTException $e) {
            throw new JsonException('Unexpected error logging in the user', 500);
        }

        return $response;
    }
}
