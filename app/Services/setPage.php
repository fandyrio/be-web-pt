<?php
    namespace App\Services;


    class setPage{
        public function setLimit($var){
            if($var === "citizen"){
                return 15;
            }
        }

        public function jumlahHalaman($total, $limit){
            return ceil($total / $limit);
        }

        public function validatePage($page, $jumlah_halaman){
            if((int)$page > (int)$jumlah_halaman && (int)$page < 1){
                return 1;
            }
            return $page;
        }

        public function skipQuery($page, $limit){
            return $page * $limit - $limit;
        }

        public function blindId($var){
            if($var === "citizen"){
                return 1;
            }
        }
    }

?>