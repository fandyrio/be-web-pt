<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_role=$request->user()->role_code;
        if(!$user_role || $user_role !== 'r-001'){
            return response()->json(['msg'=>'Access Denied'], 403);
        }
        return $next($request);
    }
}
