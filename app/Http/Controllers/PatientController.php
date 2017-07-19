<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = patient::all();

        return $patients;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $patient = new patient;

        $patient->firstname = $request->input('firstName');
        $patient->lastname = $request->input('lastName');
        $patient->care_house = $request->input('carehome');
        $patient->date_of_birth = $request->input('dateOfBirth');
        $patient->birth_location = $request->input('birthPlace');
        $patient->location = $request->input('location');

        $patient->save();

        $responseCode = 201;
        $createdPatient = [
            'id' => $patient->id,
            'firstName' => $patient->firstname,
            'lastName' => $patient->lastname,
            'carehome' => $patient->care_house,
            'dateOfBirth' => $patient->date_of_birth,
            'birthPlace' => $patient->birth_location,
            'location' => $patient->location
        ];
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'Created',
                'location' => env('APP_URL') . '/patient/' . $patient->id
            ],
            'response' => $createdPatient
        ];
        return response()->json($response, $responseCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(patient $patient)
    {
        $patient = patient::find($patient)->first();
        $responseCode = 200;
        $gotPatient = [
            'id' => $patient->id,
            'firstName' => $patient->firstname,
            'lastName' => $patient->lastname,
            'carehome' => $patient->care_house,
            'dateOfBirth' => $patient->date_of_birth,
            'birthPlace' => $patient->birth_location,
            'location' => $patient->location,
            'createdAt' => $patient->created_at
        ];
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'OK'
            ],
            'response' => $gotPatient
        ];
        return response()->json($response, $responseCode);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, patient $patient)
    {
        $patient = patient::find($patient);
        $patient->firstname = $request->firstname;
        $patient->lastname = $request->lastname;
        $patient->date_of_birth = $request->date_of_birth;
        $patient->birth_location = $request->birth_location;
        $patient->location = $request->location;
        $patient->care_house = $request->care_house;

        $patient->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(patient $patient)
    {
        patient::destroy($patient);
    }
}
