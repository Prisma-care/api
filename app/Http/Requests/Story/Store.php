<?php

namespace App\Http\Requests\Story;

use App\Album;
use App\Http\Requests\BaseRequest;
use App\Patient;
use App\Story;

class Store extends BaseRequest
{
    public function authorize()
    {
        $user = $this->getUser();
        $patient = Patient::findOrFail($this->route('patient'));
        $album = Album::findOrFail(request('albumId'));

        return $user->can('create', [Story::class, $patient, $album]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'creatorId' => 'required',
            'albumId' => 'required',
        ];
    }
}
