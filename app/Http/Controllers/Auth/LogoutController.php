<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function signout(Request $request)
    {
    	$token = JWTAuth::getToken();
        if (JWTAuth::invalidate($token)) {
        	return response()->success([], 200, 'User logged out successfully');
        }
    }
}
