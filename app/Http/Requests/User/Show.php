<?php

namespace App\Http\Requests\User;

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
        return $this->getUser()->id !== null;
    }
}
