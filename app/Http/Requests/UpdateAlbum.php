<?php

namespace App\Http\Requests;

class UpdateAlbum extends StoreAlbum
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'unique:albums',
        ];
    }
}
