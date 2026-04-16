<?php
    namespace App\Services;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

    class syncDataService{
        public function saveDataCitizen($data_citizen, $jumlah){
            $status=false;
            if($jumlah > 0){
                try{
                    DB::beginTransaction();
                        DB::table('citizen')->upsert($data_citizen, 
                            ['id_simpeg'],
                            ['nama', 'nip', 'nik', 'email', 'id_pangkat', 'id_pendidikan', 'tempat_pendidikan', 'tgl_lulus', 'tempat_lahir', 'no_hp', 'jenis_kelamin', 'id_jabatan', 'id_bagian', 'satker_id', 'masuk_kerja', 'status']
                        );
                    DB::commit();
                    $status=true;
                    $msg="Berhasil Sinkronisasi Data Pegawai";
                }catch(QueryException $e){
                    DB::rollBack();
                    $msg=$e->getMessage();
                }
            }else{
                $msg="Tidak ada data Hakim / Pegawai ditemukan";
            }

            return ['status'=>$status, 'msg'=>$msg];
        }

        public function saveDataJabatan($data, $jumlah){
            $status=false;
            if($jumlah > 0){
                try{
                    DB::beginTransaction();
                        DB::table('jabatan')->upsert($data,
                            ['id'],
                            ['jabatan', 'id_atasan_langsung', 'urutan']
                        );
                    DB::commit();
                    $status=true;
                    $msg="Data Jabatan berhasil disimpan";
                }catch(QueryException $e){
                    DB::rollBack();
                    $msg=$e->getMessage();
                }
            }else{
                $msg="Data Jabatan tidak ditemukan";
            }

            return ['status'=>$status, 'msg'=>$msg];
        }

        public function saveDataBagian($data, $jumlah){
            $status=false;
            if($jumlah > 0){
                try{
                    DB::beginTransaction();
                        DB::table('bagian')->upsert($data,
                            ['id'],
                            ['bagian', 'id_bagian_induk', 'is_induk_bagian']
                        );
                    DB::commit();
                    $status=true;
                    $msg="Data Bagian berhasil disimpan";
                }catch(QueryException $e){
                    DB::rollBack();
                    $msg=$e->getMessage();
                }
            }else{
                $msg="Data Bagian tidak ditemukan";
            }

            return ['status'=>$status, 'msg'=>$msg];
        }

        public function saveDataPangkat($data, $jumlah){
            $status=false;
            if($jumlah > 0){
                try{
                    DB::beginTransaction();
                        DB::table('pangkat')->upsert($data,
                            ['id'],
                            ['pangkat', 'status']
                        );
                    DB::commit();
                    $status=true;
                    $msg="Data Pangkat berhasil disimpan";
                }catch(QueryException $e){
                    DB::rollBack();
                    $msg=$e->getMessage();
                }
            }else{
                $msg="Data Pangkat tidak ditemukan";
            }

            return ['status'=>$status, 'msg'=>$msg];
        }

    }

?>