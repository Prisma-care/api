<?php

namespace App\Http\Requests\DefaultAlbum;

use App\Http\Requests\BaseRequest;

class Show extends BaseRequest
{
    public function authorize()
    {
        return true;
    }
}
