<?php

namespace App\Http\Controllers;

use App\User;
use JWTAuth;
use App\Http\Requests\User as UserRequest;
use Hash;

/**
 * Class UserController
 * @package App\Http\Controllers
 * @resource User
 *
 * Users are family members of a Patient
 * A User can be attached to multiple Patients
 * A User can create new Albums and Story content
 * A User can also invite other Users and connect them with a Patient
 */

class UserController extends Controller
{
    public function __contruct()
    {
        $this->middleware('jwt.auth', ['except' => 'store']);
    }

    /**
     * Return a User
     *
     * @param UserRequest\Show $request
     * @return mixed
     */
    public function show(UserRequest\Show $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $transformed = [
            'id' => $user->id,
            'email' => $user->email,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name
        ];
        return response()->success($transformed, 200, 'OK');
    }

    /**
     * Persist a New User
     *
     * @param UserRequest\Store $request
     * @return mixed
     */
    public function store(UserRequest\Store $request)
    {
        $user = new User([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
            'date_of_birth' => $request->input('dateOfBirth'),
            'birth_place' => $request->input('birthPlace'),
            'user_type' => $request->input('userType', 'family')
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
