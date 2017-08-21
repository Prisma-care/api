<?php

namespace App\Http\Requests\Invite;

use App\Http\Requests\BaseRequest;

class StoreUserConnection extends BaseRequest
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
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'patientID' => 'required|integer',
            'inviterID' => 'required|integer'
        ];
    }
}
