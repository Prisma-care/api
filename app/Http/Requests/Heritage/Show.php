<?php

namespace App\Http\Requests\Heritage;

use App\Http\Requests\BaseRequest;

class Update extends BaseRequest
{
    public function authorize()
    {
        return true;
    }
}
