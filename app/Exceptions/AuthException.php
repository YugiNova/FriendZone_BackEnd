<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthException extends Exception
{
    public $e;
    public $message;
    public $statusCode;

    public function __construct(Exception $e)
    {
        $this->e = $e;
        $this->message = $e->getMessage();
        $this->statusCode = is_null($e->getCode()) ? $e->getCode() : 500;
    }

    public function render()
    {
        if ($this->message == 'Token has expired') {
            $token = Auth::refresh(true);
            return $this->makeErrorResponse($token);
        }

        return $this->makeErrorResponse();
    }

    public function makeErrorResponse($token = null)
    {
        $response = response()->json(
            [
                'type' => 'error',
                'message' => $this->e->getMessage(),
                'status' => $this->statusCode,
            ],
            $this->statusCode,
        );

        if ($token) {
            return $response->cookie('token', $token);
        }
        return $response;
    }
}
