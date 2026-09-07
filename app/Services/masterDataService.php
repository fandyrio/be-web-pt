<?php
    namespace App\Services;

use App\Models\Bagian;
use Illuminate\Support\Facades\Crypt;
use Vinkla\Hashids\Facades\Hashids;

    class masterDataService{

        //master bagian
        public function listBagian(int $request_page){
            $data = [];
            $limit = 15;
            $total = Bagian::where("status", true)->count();
            $jumlah_halaman = ceil($total/$limit);
            
            $page = validatePage($request_page, $total);
            $skip = $page * $limit - $limit;
            $get_data = Bagian::where("status", true)->skip($skip)->take($limit)->get();
            foreach($get_data as $list_data){
                $data[]=[
                    'id'=>generateDataToken($list_data['id']),
                    'bagian'=>$list_data['bagian']
                ];
            }

            return  ['data'=>$data, 'jumlah_halaman'=>$jumlah_halaman];
        }

        //========================================

    }


?>