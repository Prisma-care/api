<?php

namespace App\Http\Controllers\Invite;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invite\SetPassword;
use App\Invite;
use App\Mail\SendPassword;
use App\User;
use Hash;
use Mail;
use View;

/**
 * Class SetPasswordController.
 *
 * @resource Invite\SetPassword
 *
 * When a User invites another user to connect to a Patient,
 * a temporary user is created if the User does not already exist.
 *
 * In this case an email invite is sent to the new User with a tokenized URL
 * They can use this to access a password (re)set page and accep their membership of Prisma
 */
class SetPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * No explicit return
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Check invite token.
     *
     * Check for a valid token and show the update password form if valid
     *
     * @param string $token
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function checkToken(string $token)
    {
        $invite = Invite::where('token', $token)->first();

        if (! $invite) {
            abort(404, 'Token already used or invalid');
        }

        $data = [
            'invite_id' => $invite->id,
            'user_id' => $invite->user_id,
            'email' => $invite->email,
            'token' => $invite->token,
        ];

        return View::make('invites.set', $data);
    }

    /**
     * Set new password.
     *
     * @param SetPassword $request
     *
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
            'password' => $new_password,
        ];

        Mail::to($user)->send(new SendPassword($data));

        return view('invites.confirmation');
    }

    /**
     * Destroy Invite Token.
     *
     * @param $token
     *
     * @throws \Exception
     */
    private function destroyToken($token)
    {
        Invite::where('token', $token)->delete();
    }
}
