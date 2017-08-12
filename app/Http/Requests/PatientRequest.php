<?php

namespace App\Http\Requests;

use App\Patient;
use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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

    public function all()
    {
        return array_replace_recursive(
            parent::all(),
            $this->route()->parameters()
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->method() === 'GET') {
            return [
                'patient' => 'required|integer|exists:patients,id'
            ];
        }

        return [
            'firstName' => 'required',
            'lastName' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'patient.required' => 'The patient id is required',
            'patient.integer'  => 'The patient id must be numeric',
            'patient.exists'  => 'There is no patient with the provided id'
        ];
    }

    public function response(array $errors)
    {
        return response()->exception($errors, 400);
    }
}
