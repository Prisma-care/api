<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
     /**
     * Connect a user with a patient.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function connect(Request $request, $patientId)
    {
        try {
            Patient::findOrFail($patientId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            return response()->exception("There is no $failingResource resource with the provided id.", 400);
        }

        // TODO make dynamic by getting from JWT Auth token
        $userId = 1;
        $patient = Patient::find($patientId);
        if ($patient->users->contains($userId)) {
            return response()->exception('The patient and user are already connected', 400);
        } elseif (!$patient->users()->attach($userId)) {
            return response()->exception('The patient and user could not be connected', 500);
        }
        return response()->success([], 200, 'OK');
    }
}
