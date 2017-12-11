<?php

namespace App\Http\Requests\HeritageAsset;

use App\Heritage;
use App\Http\Requests\BaseRequest;

class Store extends BaseRequest
{
    public function authorize()
    {
        $user = $this->getUser();
        $heritage = Heritage::findOrFail($this->route('heritage'));

        return $user->can('create', $heritage) && $user->can('update', $heritage);
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
