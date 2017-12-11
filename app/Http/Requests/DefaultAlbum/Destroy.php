<?php

namespace App\Http\Requests\DefaultAlbum;

use App\Heritage;
use App\Http\Requests\BaseRequest;

class Destroy extends BaseRequest
{
    public function authorize()
    {
        $user = $this->getUser();

        return $user->can('delete', Heritage::class);
    }
}
