<?php

namespace App\Http\Controllers\Reset;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reset\StoreReset;
use App\Http\Requests\Reset\StoreResetPassword;
use App\Mail\SendNewPassword;
use App\Mail\SendPasswordResetLink;
use App\User;
use Carbon\Carbon;
use Hash;
use Mail;
use View;

class ResetController extends Controller
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
     * @param StoreReset $request
     */
    public function store(StoreReset $request)
    {

        $email = $request->email;
        $token = str_random(40);
        $created_at = Carbon::now();

        DB::table('password_resets')->insert(
            ['email' => $email, 'token' => $token, 'created_at' => $created_at]
        );

        $user = User::where('email', $email)->get();

        $data = [
            'token' => $token
        ];

        Mail::to($user)->send(new SendPasswordResetLink($data));

        return response()->success([], 204, 'Password reset email sent');
    }

    /**
     * Check reset token
     *
     * Check for a valid token and show the update password form if valid
     *
     * @param string $token
     * @return \Illuminate\Contracts\View\View
     */
    public function checkToken(string $token)
    {
        $reset = DB::table('password_resets')->where('token', $token)->first();

        if (!$reset) {
            abort(404, 'Dit wachtwoord herstel token is niet geldig.'); // This password recovery token is not valid
        }

        $data = [
            'email' => $reset->email,
            'token' => $token
        ];

        return View::make('reset.set', $data);

    }


    /**
     *  Set a new password
     *
     * @param StoreResetPassword $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function set(SetNewPassword $request)
    {
        $email = $request->input('email');
        $new_password = Hash::make($request->input('password'));

        User::where('email', $email)->update(['password' => $new_password]);

        $this->destroyToken($request->input('token'));

        $data = [
            'user_name' => $user->first_name,
            'password' => $new_password
        ];

        Mail::to($user)->send(new SendNewPassword($data));

        return view('reset.confirmation');

    }

    /**
     * Destroy Invite Token
     *
     * Delete the token used for this reset
     * @param $token
     */
    private function destroyToken($token)
    {
        DB::table('password_resets')->where('token', $token)->delete();
    }
}
