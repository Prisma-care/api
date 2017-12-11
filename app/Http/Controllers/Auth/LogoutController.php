<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use Auth;
use App\Http\Controllers\Controller;

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
