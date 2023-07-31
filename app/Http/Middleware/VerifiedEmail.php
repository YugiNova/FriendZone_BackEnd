<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthException;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifiedEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = Auth::user();
            if(is_null($user['email_verified_at'])){
                throw new Exception("Account is not verified");
            }
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
        
        return $next($request);
    }
}
