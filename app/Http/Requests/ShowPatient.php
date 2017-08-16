<?php

namespace App\Http\Requests;

use JWTAuth;
use App\Patient;
use Illuminate\Foundation\Http\FormRequest;

class ShowPatient extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $patient = $this->route('patient');
        return $user->can('view', $patient);
    }
}
