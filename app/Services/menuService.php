<?php
    namespace App\Services;

use App\Models\Layout_view;

    class menuService{
        public function listLayout(){
            return Layout_view::all();
            
        }

        public function getLayoutName($nama){
            return Layout_view::where('judul', $nama)->first();
        }

        public function saveLayout($nama){
            if(!is_null($this->getLayoutName($nama))){
                return ['status'=>false, 'msg'=>'Data sudah ada'];
            }

            $new_layout = new Layout_view;
            $new_layout = $new_layout->judul = $nama;
            // $new_layout =  
            
        }

    }


?>