<?php

namespace App\Http\Requests\Heritage;

use App\Heritage;
use App\Http\Requests\BaseRequest;

class Store extends BaseRequest
{
    public function authorize()
    {
        $user = $this->getUser();
        return $user->can('create', Heritage::class);
    }
}
