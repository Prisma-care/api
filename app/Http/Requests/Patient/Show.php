<?php

namespace App\Http\Requests\Patient;

use App\Http\Requests\BaseRequest;

class Show extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->getUser();
        $patient = $this->route('patient');

        return $user->can('view', $patient);
    }
}
