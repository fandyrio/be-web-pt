<?php

namespace App\Http\Controllers;

use App\Services\syncDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class syncDataController extends Controller
{
    protected $syncDataService;
    public function __construct(syncDataService $sync_data_service)
    {
        // throw new \Exception('Not implemented');
        $this->syncDataService=$sync_data_service;
    }

    public function syncCitizen(){
        $url_simpeg=config('costum.api_cuti_prod');
        $curl_url=$url_simpeg."/sync-citizen";
        $token=config('costum.token_simpeg');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $curl_url,
        //CURLOPT_URL => 'http://backend-cuti.pn-bengkulu.go.id/api/get-hakim-dus',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        //CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST=> 0,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept:application/json',
            'Authorization:Bearer '.$token
            ),
        ));
        $response = curl_exec($curl);
        $decode=json_decode($response, true);
        // $info=curl_getinfo($curl);
        $err = curl_error($curl);
        // var_dump($err);  //if you need
        curl_close($curl);
        if(isset($decode['message'])){
            return response()->json([ 'status'=>false, 'msg'=>'Error API Cuti' ]);
        }else{
            $data=$decode['data'];
            $jumlah=$decode['jumlah'];
            $mapped=collect($data)->map(function ($item) {
                $item['satker_id']=$item['satker'];
                $item['id_pangkat']=$item['pangkat'];
                $item['id_pendidikan']=$item['pendidikan'];
                $item['id_simpeg']=$item['id'];
                $item['nik']='xxxxxxxx';
                $item['created_at']=date("Y-m-d H:i:s");
                $item['updated_at']=date("Y-m-d H:i:s");
                $item['slug']=Str::slug($item['nama']);
                unset($item['satker']);
                unset($item['foto']);
                unset($item['pangkat']);
                unset($item['pendidikan']);
                unset($item['id']);
                return $item;
            })->toArray();

            // $save_citizen=$this->saveCitizen($data, $jumlah);
            $save_citizen=$this->syncDataService->saveDataCitizen($mapped, $jumlah);
            return response()->json(['status'=>$save_citizen['status'], 'data'=>$save_citizen['msg']]);
        }
    }

    public function syncJabatan(){
        $url_simpeg=config('costum.api_cuti_prod');
        $curl_url=$url_simpeg."/sync-jabatan";
        $token=config('costum.token_simpeg');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $curl_url,
        //CURLOPT_URL => 'http://backend-cuti.pn-bengkulu.go.id/api/get-hakim-dus',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        //CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST=> 0,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept:application/json',
            'Authorization:Bearer '.$token
            ),
        ));
        $response = curl_exec($curl);
        $decode=json_decode($response, true);
        // $info=curl_getinfo($curl);
        $err = curl_error($curl);
        // var_dump($err);  //if you need
        curl_close($curl);
        if(isset($decode['message'])){
            return response()->json([ 'status'=>false, 'msg'=>'Error API Cuti' ]);
        }else{
            $data=$decode['data'];
            $jumlah=$decode['jumlah'];

            // $save_citizen=$this->saveCitizen($data, $jumlah);
            $mapped=collect($data)->map(function ($item) {
                $item['urutan']=0;
                return $item;
            })->toArray();
            $save=$this->syncDataService->saveDataJabatan($mapped, $jumlah);
            return response()->json(['status'=>$save['status'], 'data'=>$save['msg']]);
        }
    }

    public function syncBagian(){
        $url_simpeg=config('costum.api_cuti_prod');
        $curl_url=$url_simpeg."/get-all-bagian-nf";
        $token=config('costum.token_simpeg');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $curl_url,
        //CURLOPT_URL => 'http://backend-cuti.pn-bengkulu.go.id/api/get-hakim-dus',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        //CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST=> 0,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept:application/json',
            'Authorization:Bearer '.$token
            ),
        ));
        $response = curl_exec($curl);
        $decode=json_decode($response, true);
        // $info=curl_getinfo($curl);
        $err = curl_error($curl);
        // var_dump($err);  //if you need
        curl_close($curl);
        if(isset($decode['message'])){
            return response()->json([ 'status'=>false, 'msg'=>'Error API Cuti' ]);
        }else{
            $data=$decode['data'];
            $jumlah=$decode['total'];

            // $save_citizen=$this->saveCitizen($data, $jumlah);
            $save=$this->syncDataService->saveDataBagian($data, $jumlah);
            return response()->json(['status'=>$save['status'], 'data'=>$save['msg']]);
        }
    }

    public function syncPangkat(){
        $url_simpeg=config('costum.api_cuti_prod');
        $curl_url=$url_simpeg."/get-all-pangkat";
        $token=config('costum.token_simpeg');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $curl_url,
        //CURLOPT_URL => 'http://backend-cuti.pn-bengkulu.go.id/api/get-hakim-dus',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        //CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST=> 0,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept:application/json',
            'Authorization:Bearer '.$token
            ),
        ));
        $response = curl_exec($curl);
        $decode=json_decode($response, true);
        // $info=curl_getinfo($curl);
        $err = curl_error($curl);
        // var_dump($err);  //if you need
        curl_close($curl);
        if(isset($decode['message'])){
            return response()->json([ 'status'=>false, 'msg'=>'Error API Cuti' ]);
        }else{
            $data=$decode['data'];
            $jumlah=$decode['total'];

            $mapped=collect($data)->map(function($item) {
                $item['status'] = true;
                $item['id']=$item['uid'];
                unset($item['uid']);
                return $item;
            })->toArray();

            // $save_citizen=$this->saveCitizen($data, $jumlah);
            $save=$this->syncDataService->saveDataPangkat($mapped, $jumlah);
            return response()->json(['status'=>$save['status'], 'data'=>$save['msg']]);
        }
    }
}
