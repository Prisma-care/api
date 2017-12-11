<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Patient;

/**
 * Class ConnectionController.
 *
 * @resource Connection
 *
 * Controller used to connect Users to Patients
 */

class ConnectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Connect a user with a patient.
     *
     * @param int $patientId
     *
     * @return \Illuminate\Http\Response
     */
    public function connect($patientId)
    {
        $userId = JWTAuth::parseToken()->authenticate()->id;
        $patient = Patient::findOrFail($patientId);
        if ($patient->users->contains($userId)) {
            return response()->exception('The patient and user are already connected', 400);
        }

        $patient->users()->attach($userId);
        return response()->success([], 200, 'OK');
    }

    /**
     * Disconnect a user from a patient.
     *
     * @param int $patientId
     *
     * @return \Illuminate\Http\Response
     */
    public function disconnect($patientId)
    {
        $userId = JWTAuth::parseToken()->authenticate()->id;
        $patient = Patient::findOrFail($patientId);
        if (!$patient->users->contains($userId)) {
            return response()->exception('The patient and user are not connected', 400);
        }

        $patient->users()->detach($userId);
        return response()->success([], 200, 'OK');
    }
}
