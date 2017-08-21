<?php

namespace App\Http\Requests\DefaultAlbum;

use App\Heritage;
use App\Http\Requests\BaseRequest;

class Index extends BaseRequest
{
    public function authorize()
    {
        return true;
    }
}
