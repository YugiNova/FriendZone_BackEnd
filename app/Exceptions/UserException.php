<?php

namespace App\Exceptions;

use Exception;

class UserException extends Exception
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
