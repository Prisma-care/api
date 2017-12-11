<?php

namespace App\Http\Requests\Story;

use App\Patient;
use App\Http\Requests\BaseRequest;

class Update extends BaseRequest
{
    public function authorize()
    {
        $user = $this->getUser();
        $patient = Patient::findOrFail($this->route('patient'));

        return $user->can('view', $patient);
    }
}
