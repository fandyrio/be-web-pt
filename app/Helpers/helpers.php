<?php

use Illuminate\Support\Facades\Crypt;
use Vinkla\Hashids\Facades\Hashids;

    if(!function_exists('validatePage')){
        function validatePage($request_page, $total_page){
            $page = $request_page;
            if($request_page <= 0 || $request_page > $total_page){
                $page = 1;
            }
            return $page;
        }
    }

    if(!function_exists("generateDataToken")){
        function generateDataToken($id){
            $enc_numb = Hashids::encode($id);
            $enc_str = Crypt::encrypt($id);

            return $enc_numb."-".$enc_str;
        }
    }

?>