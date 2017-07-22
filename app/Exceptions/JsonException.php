<?php

namespace App\Exceptions;

use Exception;

class JsonException extends Exception
{
    public $message;
    public $code;

    public function __construct($message, $code)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
