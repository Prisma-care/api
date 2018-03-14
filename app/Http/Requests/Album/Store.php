<?php

namespace App\Http\Requests\Album;

use App\Http\Requests\BaseRequest;
use App\Patient;
use Illuminate\Validation\Rule;

class Store extends BaseRequest
{
    public function authorize()
    {
        $user = $this->getUser();
        $patient = Patient::findOrFail($this->route('patient'));

        return $user->can('view', $patient);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [
                'required',
                Rule::unique('albums')->where(function ($query) {
                    $patientId = Patient::findOrFail($this->route('patient'))->id;
                    $query->where('patient_id', '=', $patientId);
                }),
            ],
        ];
    }
}
