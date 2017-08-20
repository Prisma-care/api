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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * check for a valid token and show the update password form
     *
     * @param string $token
     * @return \Illuminate\Contracts\View\View
     */
    public function checkToken(string $token)
    {
        $invite = Invite::where('token', $token)->first();

        $data = [
            'invite_id' => $invite->id,
            'user_id' => $invite->user_id,
            'email' => $invite->email,
            'token' => $invite->token
        ];

        return View::make('invites.set', $data);

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
