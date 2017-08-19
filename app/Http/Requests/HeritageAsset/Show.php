<?php

namespace App\Http\Requests\HeritageAsset;

use App\Http\Requests\BaseRequest;

class Show extends BaseRequest
{
    public function authorize()
    {
        return true;
    }
}
