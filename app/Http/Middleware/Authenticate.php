<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthException;
use Closure;
use Exception;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

class Authenticate 
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle(Request $request, Closure $next):Response|JsonResponse
    {
        try {
            //check if cookie has token
            if(!$request->cookie('token')){
                throw new Exception('Token not found'); 
            }

            //check if token is expire
            $token = Auth::payload();

            //authenticate token
            $token = Auth::authenticate();

            return $next($request);
        } catch (\Throwable $e) {
           throw new AuthException($e);           
        }
    }
}
