<?php

namespace App\Http\Middleware;

use App\Models\Apps_token;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class waSignin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $token = $request->token;
        // $apps_name = $request->apps_name;
        if(is_null($request->header('Authorization'))){
            return response()->json(['status'=>false, 'msg'=>'Access Denied[00]']);
        }
        $auth = $request->header('Authorization');
        $explode = explode("-", $auth);
        $apps_name = $explode[0];
        $token = $explode[1];

        $get_data = Apps_token::where('name', $apps_name)->first();
        if(is_null($get_data)){
            return response()->json(['status'=>false, 'msg'=>'Access Denied[01]']);
        }
        $check = Hash::check($token, $get_data->hashed_key);
        if(!$check){
            return response()->json(['status'=>false, 'msg'=>'Access Denied[02]']);
        }

        return $next($request);
    }
}
