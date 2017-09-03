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

        if(!$invite) { abort(404,'Token already used or invalid'); }

        $data = [
            'invite_id' => $invite->id,
            'user_id' => $invite->user_id,
            'email' => $invite->email,
            'token' => $invite->token
        ];

        return View::make('invites.set', $data);

    }


    /**
     * set the new password
     *
     * @param SetPassword $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function set(SetPassword $request)
    {
        $user_id = $request->input('user_id');
        $email = $request->input('email');

        $user = User::where('id', $user_id)
            ->where('email', $email)
            ->first();

        $new_password = $request->input('password');

        $user->password = Hash::make($new_password);
        $user->save();

        $this->destroyToken($request->input('token'));

        $data = [
            'user_name' => $user->first_name,
            'password' => $new_password
        ];

        Mail::to($user)->send(new SendPassword($data));

        return view('invites.confirmation');

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
