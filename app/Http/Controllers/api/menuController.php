<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\menuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class menuController extends Controller
{
    protected $menuService;
    protected $status;
    protected $http_code;
    public function __construct(menuService $menu_service)
    {
        $this->status = false;
        $this->http_code=404;
        $this->menuService = $menu_service;
    }

    /**
     * List Layout
     * @group Admin - Menu Website
     * @authenticated
     */
    public function getLayout(){
        $msg = "Data Not Available";
        $layout = $this->menuService->listLayout();
        $jumlah = $layout->count();
        if($jumlah > 0){
            $msg="Data available";
            $this->status = true;
            $this->http_code = 200;
        }
        return response()->json(['status'=>true, 'msg'=>$msg, 'data'=>$layout], $this->http_code);
    }

    /**
     * Add Layout
     * @group Admin - Menu Website
     * @authenticated
     */
    public function saveLayout(Request $request){
        $validate = Validator::make($request->all(), [
            'nama'=>['required', 'string']
        ]);

        if($validate->fails()){
            return response()->json(['status'=>$this->status, 'msg'=>$validate->errors()->first()]);
        }
        
    }

}
