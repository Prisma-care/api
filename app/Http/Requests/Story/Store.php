<?php

namespace App\Http\Requests\Story;

use App\Patient;
use App\Http\Requests\BaseRequest;

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
            'description' => 'required',
            'creatorId' => 'required',
            'albumId' => 'required'
        ];
    }
}
