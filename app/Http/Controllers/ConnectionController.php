<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
     /**
     * Connect a user with a patient.
     *
     * @param  int $patientId
     * @return \Illuminate\Http\Response
     */
    public function connect($patientId)
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
        }

        $patient->users()->attach($userId);
        return response()->success([], 200, 'OK');
    }

     /**
     * Disconnect a user from a patient.
     *
     * @param  int $patientId
     * @return \Illuminate\Http\Response
     */
    public function disconnect($patientId)
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
        if (!$patient->users->contains($userId)) {
            return response()->exception('The patient and user are not connected', 400);
        }

        $patient->users()->detach($userId);
        return response()->success([], 200, 'OK');
    }
}
