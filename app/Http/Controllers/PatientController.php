<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Patient;
use App\Http\Requests\Patient as PatientRequest;

/**
 * Class PatientController.
 *
 * @resource Patient
 *
 * A Patient is a person who has dementia and receives nursing care.
 * Patients are the loved ones of family members (Users)
 *
 * This Controller allows new Patients to be created and a Patient request returned for a particular Patient
 */
class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Persist a new Patient to storage.
     *
     * @param PatientRequest\Store $request
     *
     * @return mixed
     */
    public function store(PatientRequest\Store $request)
    {
        $patient = new Patient([
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
            'care_home' => $request->input('careHome'),
            'date_of_birth' => $request->input('dateOfBirth'),
            // NYI
            'birth_place' => $request->input('birthPlace'),
            'location' => $request->input('location'),
        ]);

        if (! $patient->save()) {
            return response()->exception('The patient could not be created', 500);
        }

        $patient->prepopulate();

        $createdPatient = [
            'id' => $patient->id,
            'firstName' => $patient->first_name,
            'lastName' => $patient->last_name,
            'careHome' => $patient->care_home,
            'dateOfBirth' => $patient->date_of_birth,
            'birthPlace' => $patient->birth_place,
            'location' => $patient->location,
        ];

        $user = JWTAuth::parseToken()->authenticate();
        $patient->users()->attach($user->id);

        $location = $request->url().'/'.$patient->id;

        return response()->success($createdPatient, 201, 'Created', $location);
    }

    /**
     * Return the specified Patient if permitted by the Form Request.
     *
     * @param PatientRequest\Show $request
     * @param Patient             $patient
     *
     * @return mixed
     */
    public function show(PatientRequest\Show $request, Patient $patient)
    {
        $gotPatient = [
            'id' => $patient->id,
            'firstName' => $patient->first_name,
            'lastName' => $patient->last_name,
            'careHome' => $patient->care_home,
            'dateOfBirth' => $patient->date_of_birth,
            'birthPlace' => $patient->birth_place,
            'location' => $patient->location,
            'createdAt' => $patient->created_at,
        ];

        return response()->success($gotPatient, 200, 'OK');
    }
}
