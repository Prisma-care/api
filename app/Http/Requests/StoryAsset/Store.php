<?php

namespace App\Http\Requests\StoryAsset;

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
        if (request('assetType') === 'youtube') {
            return [
                'asset' => 'required|url',
            ];
        }

        return [];
    }
}
