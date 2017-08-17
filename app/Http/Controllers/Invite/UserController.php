<?php

namespace App\Http\Controllers\Invite;

use App\Http\Controllers\Controller;
use App\Mail\Invitation;
use App\Patient;
use App\User;
use Hash;
use Mail;

class UserController extends Controller
{

    /**
     * @param StoreUserConnection $request
     * @return mixed
     */
    public function store(StoreUserConnection $request)
    {

        $user_data = [
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName')
        ];

        $user = User::create($user_data);

        if (!$user->save()) {
            return response()->exception('Unexpected error while creating the user', 500);
        }

        app('auth.password.tokens')->create($user);

        $patient = Patient::findOrFail($request->input($patientId));
        $patient->users()->attach($user->id);

        Mail::to($user)->send(new Invitation($patient));

        return response()->success('Invitation email sent', 204);
    }
}
