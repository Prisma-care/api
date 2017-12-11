<?php

namespace App\Http\Requests\Patient;

use App\Http\Requests\BaseRequest;

class Store extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstName' => 'required',
            'lastName' => 'required',
        ];
    }
}
