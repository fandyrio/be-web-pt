<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\authService;
use App\Services\citizenService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class authController extends Controller
{
    protected $citizenService;
    protected $authService;
    public function __construct(citizenService $citizen_service, authService $auth_service)
    {
        $this->citizenService=$citizen_service;
        $this->authService=$auth_service;
    }
    /**
     * Login User
     * @anuathenticated
     * @group Admin - Authentication
     *
     * @bodyParam username string required Username user. Used: NIP
     * @bodyParam password string required Password user. Default: NIP
     *
     * @response 200 {
     *   "status": true,
     *   "msg": "Login berhasil",
     *   "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
     * }
     */
    public function login(Request $request){
        $status=false;
        $token=null;
        $rft=null;
        try{
            $request->validate([
                'username'=>['required', 'min:16'],
                'password' => ['required', 'min:5']
            ]);
            $check_user=$this->citizenService->getCitizenBy($request->username, 'nip');
            if(!is_null($check_user)){
                $citizen_id=$check_user['id'];
                $login=$this->authService->checkUser($citizen_id, $request->password);
                $status=$login['status'];
                $msg=$login['msg'];
                $token=$login['token'];
                $rft=$login['rft'];
                if($status === true){
                    return response()->json(['status'=>$status, 'msg'=>$msg, 'token'=>$token])->withCookie((cookie('rft-web-pt', $rft, 60*24*7, '/', null, true, true, false, 'Lax')));
                }
            }else{
                $msg="Data tidak ditemukan";
            }
        }catch(ValidationException $e){
            $msg=$e->validator->errors()->first();
        }
        return response()->json(['status'=>false, 'msg'=>$msg], 401);
    }

    public function refreshToken(Request $request){
        $cookie=$request->cookie('rft-web-pt');
        try{
            $payload=JWTAuth::setToken($cookie)->getPayload();
            if($payload->get('type') !== 'refresh'){
                return response()->json(['msg'=>'Access Denied'], 401);
            }
            $user=JWTAuth::setToken($cookie)->toUser();
            JWTAuth::setToken($cookie)->invalidate();

            $new_access_token=JWTAuth::fromUser($user);
            $new_refresh_token=JWTAuth::claims(['type'=>'refresh'])->fromUser($user);

            return response()->json(['token'=>$new_access_token])->withCookie((cookie('rft-web-pt', $new_refresh_token, 60*24*7, '/', null, true, true, false, 'Lax')));

        }catch(\Exception $e){
            return response()->json(['msg'=>'Token Expired'], 401)->withCookie((cookie('rft-web-pt', null, -1, '/', null, true, true, false, 'Lax')));
        }
    }
}
