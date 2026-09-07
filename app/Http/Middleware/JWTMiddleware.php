<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            JWTAuth::parseToken()->authenticate();
        }
        catch(TokenExpiredException $e){
            return response()->json(['status'=>false, 'msg'=>'Token Expired'], 401);
        }catch(TokenInvalidException $e){
            return response()->json(['status'=>false, 'msg'=>'Invalid Token'], 401);
        }
        catch(JWTException $e){
            return response()->json(['status'=>false, 'msg'=>'Token required'], 401);
        }
        return $next($request);
    }
}
