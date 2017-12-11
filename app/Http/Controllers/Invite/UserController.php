<?php

namespace App\Http\Controllers\Invite;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invite\StoreUserConnection as StoreUserConnection;
use App\Invite;
use App\Mail\Invitation;
use App\Patient;
use App\User;
use Hash;
use Mail;

/**
 * Class UserController.
 *
 * @resource Invite\User
 *
 * When a User invites another user to connect to a Patient,
 * a temporary user is created if the User does not already exist.
 *
 * In this case an email invite is sent to the new User with a tokenized URL
 * They can use this to access a password (re)set page and accep their membership of Prisma
 */
class UserController extends Controller
{
    /**
     * Persist New Invited User.
     *
     * Checks for the existence of an invited User
     * Creates then if they don't already exist and then generates a token and sends an email invite
     *
     * @param StoreUserConnection $request
     *
     * @return mixed
     */
    public function store(StoreUserConnection $request)
    {
        $email = $request->input('email');

        $user_data = [
            'password' => Hash::make(str_random(40)),
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
        ];

        $patientId = $request->input('patientId');
        $inviterId = $request->input('inviterId');

        $user = User::firstOrCreate(['email' => $email], $user_data);

        // TO DO: differentiate between new and existing users
        if ($user->wasRecentlyCreated === true) {
            // we'll send an introductory invite
        } else {
            // we'll send a new patient connection notification
        }

        $inviter = User::find($inviterId)->full_name;

        $patient = Patient::findOrFail($patientId);
        $patient->users()->attach($user->id);
        $patient_name = $patient->full_name;

        $token = str_random(40);

        $invite = [
            'email' => $user->email,
            'user_id' => $user->id,
            'token' => $token,
            'patient_id' => $patientId,
            'inviter_id' => $inviterId,
        ];

        Invite::create($invite);

        $data = [
            'patient' => $patient_name,
            'inviter' => $inviter,
            'token' => $token,
        ];

        Mail::to($user)->send(new Invitation($data));

        return response()->success([], 204, 'Invitation email sent');
    }
}
