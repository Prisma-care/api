<?php

namespace App\Http\Controllers\Invite;

use App\Mail\Invitation;
use Mail;
use App\Http\Controllers\Controller;
use App\User;
use App\Patient;
use Hash;
use Illuminate\Http\Request;
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
            'password' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'patientID' => 'required'
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

        // TO DO : connect the user to the patient
        // TO DO : issue and email invite

        $createdUser = [
            'id' => $user->id,
            'email' => $user->email
        ];

        $location = $request->url() . '/' . $user->id;
        return response()->success($createdUser, 201, 'Created', $location);
    }
}
