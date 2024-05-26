<?php

namespace Maksatsaparbekov\KuleshovAuth\Exceptions;

use Exception;

class LogoutSuccessfulException extends Exception
{
    protected $message;
    protected $code;

    public function __construct($message = "Вы успешно вышли!", $code = 200)
    {
        parent::__construct($message, $code);
    }

    public function render($request)
    {
        return response()->json([
            'status' => $this->code,
            'message' => $this->message,
        ], $this->code);
    }
}