<?php

namespace App\Http\Requests;

use JWTAuth;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            //
        ];
    }

    public function getUser()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    public function response(array $errors)
    {
        return response()->exception($errors, 400);
    }
}
