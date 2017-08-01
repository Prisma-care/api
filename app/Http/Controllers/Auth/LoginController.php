<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->exception($validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->exception('Invalid credentials', 401);
            } else {
                $userId = Auth::user()->id;
                return response()->success([
                    'id' => $userId,
                    'token' => $token
                ], 200, 'OK');
            }
        } catch (JWTException $e) {
            return response()->exception($e->getMessage(), 500);
            return response()->exception('Unexpected error while logging in the user', 500);
        }

        return $response;
    }
}
