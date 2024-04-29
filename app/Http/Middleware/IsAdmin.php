<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if()
        if(Auth::guard('admin')->check()){
            return $next($request);
            response()->json([
                'status' => 'Forbidden',
                'message' => 'You are not the administrator'
            ], 403)->send();
            die;
        }
        if(Auth::guard('player')->check()){
            response()->json([
                'message' => 'Unauthenticated'
             ], 401)->send();
            die;
        }
    }
}
