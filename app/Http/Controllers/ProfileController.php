<?php

namespace App\Http\Controllers;

use Validator;
use App\Profile;
use App\Exceptions\JsonException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
            throw new JsonException($validator->errors(), 400);
        }

        $profile = new Profile;
        $profile->firstname = $request->input('firstName');
        $profile->lastname = $request->input('lastName');
        $profile->care_house = $request->input('carehome');
        $profile->date_of_birth = $request->input('dateOfBirth');
        $profile->birth_location = $request->input('birthPlace');
        $profile->location = $request->input('location');

        $profile->save();

        $createdPatient = [
            'id' => $profile->id,
            'firstName' => $profile->firstname,
            'lastName' => $profile->lastname,
            'carehome' => $profile->care_house,
            'dateOfBirth' => $profile->date_of_birth,
            'birthPlace' => $profile->birth_location,
            'location' => $profile->location
        ];

        $location = $request->url() . '/' . $profile->id;
        return response()->success($createdPatient, 201, 'Created', $location);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show($patientId)
    {
        try {
            Profile::findOrFail($patientId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            throw new JsonException("There is no $failingResource resource with the provided id.", 400);
        }

        $patient = Profile::find($patientId)->first();
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

        return response()->success($gotPatient, 200, 'OK');
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
