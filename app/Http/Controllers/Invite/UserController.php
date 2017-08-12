<?php

namespace App\Http\Controllers\Invite;

use App\Http\Controllers\Controller;
use App\Mail\Invitation;
use App\Patient;
use App\User;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Validator;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'patientID' => 'required|integer',
            'inviterID' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->exception($validator->errors(), 400);
        }

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

        return response()->success('Invite email sent', 204);
    }
}
