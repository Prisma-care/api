<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Signin;
use App\User;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{

    public function signin(Signin $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->exception('Invalid credentials', 401);
            } else {
                $userId = Auth::user()->id;
                $user = User::find($userId);
                $patients = $user->patients()
                    ->select(['patient_id','first_name','last_name'])
                    ->get()->toArray();

                return response()->success([
                    'id' => $userId,
                    'token' => $token
                ], 200, 'OK')
                ->header('Authorization', "Bearer $token")
                ->header('Access-Control-Expose-Headers', 'Authorization');
            }
        } catch (JWTException $e) {
            return response()->exception($e->getMessage(), 500);
        }

        return $response;
    }
}
