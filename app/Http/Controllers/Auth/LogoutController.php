<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use JWTAuth;

/**
 * Class LogoutController.
 *
 * @resource Auth
 */
class LogoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Log out a User.
     *
     * @return mixed
     */
    public function signout()
    {
        $token = JWTAuth::getToken();
        if (JWTAuth::invalidate($token)) {
            Auth::logout();

            return response()->success([], 200, 'User logged out successfully');
        }
    }
}
