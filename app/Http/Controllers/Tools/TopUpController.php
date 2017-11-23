<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\User;
use App\Album;
use App\Heritage;
use App\Story;
use App\Patient;

class TopUpController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * No explicit return
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

}