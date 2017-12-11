<?php

namespace App\Http\Requests\User;

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
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
        ];
    }
}
