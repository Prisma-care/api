<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\StoreUser;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * @param StoreUser $request
     * @return mixed
     */
    public function store(StoreUser $request)
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
