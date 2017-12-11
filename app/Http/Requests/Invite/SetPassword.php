<?php

namespace App\Http\Requests\Invite;

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
            'email' => 'required|email|max:191',
            'password' => 'required|max:191',
            'user_id' => 'required|integer',
            'token' => 'required|string',
        ];
    }
}
