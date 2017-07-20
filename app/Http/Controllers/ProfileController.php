<?php

namespace App\Http\Controllers;

use Validator;
use App\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::all();

        return $profiles;
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
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => $validator->errors()
            ]);
        }
        $profile = new Profile;

        $profile->firstname = $request->input('firstName');
        $profile->lastname = $request->input('lastName');
        $profile->care_house = $request->input('carehome');
        $profile->date_of_birth = $request->input('dateOfBirth');
        $profile->birth_location = $request->input('birthPlace');
        $profile->location = $request->input('location');

        $profile->save();

        $responseCode = 201;
        $createdPatient = [
            'id' => $profile->id,
            'firstName' => $profile->firstname,
            'lastName' => $profile->lastname,
            'carehome' => $profile->care_house,
            'dateOfBirth' => $profile->date_of_birth,
            'birthPlace' => $profile->birth_location,
            'location' => $profile->location
        ];
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'Created',
                'location' => $request->url() . '/' . $profile->id
            ],
            'response' => $createdPatient
        ];
        return response()->json($response, $responseCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $patient)
    {
        try {
            Profile::findOrFail($patient);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            return response()->json([
                'code' => 400,
                'message' => "There is no $failingResource resource with the provided id."
            ]);
        }

        $patient = Profile::find($patient)->first();
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
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        $profile = Profile::find($profile);
        $profile->firstname = $request->firstname;
        $profile->lastname = $request->lastname;
        $profile->date_of_birth = $request->date_of_birth;
        $profile->birth_location = $request->birth_location;
        $profile->location = $request->location;
        $profile->care_house = $request->care_house;

        $profile->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        Profile::destroy($profile);
    }
}
