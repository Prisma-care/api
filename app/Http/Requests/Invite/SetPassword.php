<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class SetPassword extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'email|max:255',
            'password' => 'required|max:255',
        ];
    }
}
