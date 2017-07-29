<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            Auth::logout();
        	return response()->success([], 200, 'User logged out successfully');
        }
    }
}
