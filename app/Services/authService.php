<?php
    namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

    class authService{
        public function checkUser($citizen_id, $password){
            $status=false;
            $refresh_token=null;
            $token=null;
            $get_data=User::join('roles as r', function($join){
                                $join->on('r.code', 'users.role_code')
                                    ->where('is_active', true);
                            })
                            ->join('citizen as c', 'c.id', 'users.citizen_id')
                            ->select('c.nama', 'r.rolename', 'r.code as role_code', 'users.*')
                            ->where('citizen_id', $citizen_id)
                            ->first();
            if(!is_null($get_data)){
                $password_db=$get_data['password'];
                $check_pwd=Hash::check($password, $password_db);
                if($check_pwd){
                    $token=JWTAuth::fromUser($get_data);
                    $refresh_token=JWTAuth::claims(['type'=>'refresh'])->fromUser($get_data);
                    $status=true;
                    $msg="Login Berhasil";
                }else{
                    $msg="Username dan Password tidak cocok";
                }
            }else{
                $msg="Data tidak ditemukan" ;
            }

            return ['status'=>$status, 'msg'=>$msg, 'token'=>$token, 'rft'=>$refresh_token];
        }
    }

?>