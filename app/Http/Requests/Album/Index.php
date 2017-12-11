<?php

namespace App\Http\Requests\Album;

use App\Patient;
use App\Http\Requests\BaseRequest;

class Index extends BaseRequest
{
    public function authorize()
    {
        $user = $this->getUser();
        $patient = Patient::findOrFail($this->route('patient'));

        return $user->can('view', $patient);
    }
}
