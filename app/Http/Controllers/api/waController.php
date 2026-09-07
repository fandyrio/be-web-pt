<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\waService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class waController extends Controller
{
    protected $waService;

    public function __construct(waService $wa_service)
    {
        // throw new \Exception('Not implemented');
        $this->waService = $wa_service;
    }

    public function sendWa(Request $request){
        $status = false;
        $msg = "Unprocess Data";
        try{
            $request->validate([
                'msg'=>['required', 'string'],
                'reciver'=>['required', 'string'],
                'type'=>['required', 'string']
            ]);

            $send_msg = $this->waService->sendWa($request->msg, $request->reciver, $request->type);
            $status = $send_msg['status'];
            $msg = $send_msg['msg'];
            
        }catch(ValidationException $e){
            $msg = $e->validator->errors()->first();
        }

        return response()->json(['status'=>$status, 'msg'=>$msg]);
    }
}
