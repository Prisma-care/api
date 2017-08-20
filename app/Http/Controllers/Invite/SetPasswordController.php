<?php

namespace App\Http\Controllers\Invite;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invite\SetPassword;
use App\Mail\SendPassword;
use App\Invite;
use App\User;
use Hash;
use Mail;
use View;

class SetPasswordController extends Controller
{

    /**
     * Where to redirect users after setting their password.
     *
     * @var string
     */
    protected $redirectTo = '/accepted';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function setPassword(SetPassword $request)
    {

    }
    /**
     * Delete the token used for this reset
     * @param $token
     */
    public function destroyToken($token)
    {
        Invite::where('token', $token)->delete();
    }
}
