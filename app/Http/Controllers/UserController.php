<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'firstName' => 'required',
            'lastName' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->exception($validator->errors(), 400);
        }

        $user = new User([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
            'date_of_birth' => $request->input('dateOfBirth'),
            'birth_place' => $request->input('birthPlace'),
            'user_type' => $request->input('userType','family')
        ]);

        if (!$user->save()) {
            return response()->exception('Unexpected error while creating the user', 500);
        }

        $createdUser = [
            'id' => $user->id,
            'email' => $user->email
        ];
        $location = $request->url() . '/' . $user->id;
        return response()->success($createdUser, 201, 'Created', $location);
    }
}
