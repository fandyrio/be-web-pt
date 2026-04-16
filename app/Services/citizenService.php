<?php
    namespace App\Services;

use App\Models\Citizen;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;

    class citizenService{
        protected $setPage;
        public function __construct(setPage $set_page)
        {
            // throw new \Exception('Not implemented');
            $this->setPage = $set_page;
        }

        public function getCitizenBy($value, $var){
            $get_user=Citizen::where($var, $value)->first();
            return $get_user;
        }

        public function getCitizenPage($page){
            
            $total=Citizen::where('status', true)->count();
            $limit = $this->setPage->setLimit('citizen');
            $jumlah_halaman = $this->setPage->jumlahHalaman($total, $limit);
            $skip = $this->setPage->skipQuery($page, $limit);
            $data=null;
            $status=false;
            $msg="";
            try{
                $get_data=Citizen::join('pangkat as p', 'p.id', 'citizen.id_pangkat')
                            ->join('bagian as b', 'b.id', 'citizen.id_bagian')
                            ->join('jabatan as j', 'j.id', 'citizen.id_jabatan')
                            ->select('citizen.nama', 'p.pangkat', 'j.jabatan', 'citizen.id_pendidikan', 'citizen.tempat_pendidikan',  'b.bagian', 'citizen.penghargaan', 'citizen.riwayat_jabatan', 'citizen.id as id_citizen', 'citizen.slug', 'citizen.foto')
                            ->where('citizen.status', true)
                            ->skip($skip)->take($limit)
                            ->get();
                $x=0;
                foreach($get_data as $list_data){
                    $flaging_blind=$this->setPage->blindId('citizen');
                    $blind_id=(int)$list_data['id_citizen'] * (int)$flaging_blind;
                    $data[$x]['slug']=Hashids::encode($blind_id)."-".$list_data['slug'];
                    $data[$x]['nama']=$list_data['nama'];
                    $data[$x]['pangkat']=$list_data['pangkat'];
                    $data[$x]['bagian']=$list_data['bagian'];
                    $data[$x]['jabatan']=$list_data['jabatan'];
                    $data[$x]['pendidikan']=$list_data['id_pendidikan']." ".$list_data['tempat_pendidikan'];
                    $data[$x]['riwayat_jabatan']=$list_data['riwayat_jabatan'];
                    $data[$x]['penghargaan']=$list_data['penghargaan'];
                    $data[$x]['foto']=$list_data['foto'];
                    $x++;
                }
                $status=true;
            }catch(QueryException $e){
                $msg=$e->getMessage();
            } 
            
            return ['status'=>$status, 'msg'=>$msg,'jumlah'=>$total, 'jumlah_halaman'=>$jumlah_halaman, 'data'=>$data];
        }

        public function getDetilCitizenAdmin($slug, $id_citizen){
            $status=false;
            $data=null;
            $decode_id_citizen=Hashids::decode($id_citizen);
            try{
                if(empty($decode_id_citizen)){
                    throw new \Exception('Data tidak ditemukan');
                }
                $get_data=Citizen::join('pangkat as p', 'p.id', 'citizen.id_pangkat')
                                ->join('bagian as b', 'b.id', 'citizen.id_bagian')
                                ->join('jabatan as j', 'j.id', 'citizen.id_jabatan')
                                ->select('citizen.nama', 
                                            'p.pangkat', 
                                            'j.jabatan', 
                                            'citizen.id_pendidikan', 
                                            'citizen.tempat_pendidikan',  
                                            'b.bagian', 
                                            'citizen.penghargaan', 
                                            'citizen.riwayat_jabatan', 
                                            'citizen.id as id_citizen', 
                                            'citizen.slug', 
                                            'citizen.nip', 
                                            'citizen.nik', 
                                            'citizen.email', 
                                            'citizen.no_hp', 
                                            'citizen.masuk_kerja', 
                                            'citizen.tgl_lulus', 
                                            'citizen.tempat_lahir', 
                                            'citizen.tanggal_lahir', 
                                            'citizen.foto')
                                ->where('citizen.status', true)
                                ->where('citizen.id', $decode_id_citizen[0])
                                ->where('citizen.slug', $slug)
                                ->first();
                if(is_null($get_data)){
                    throw new \Exception('Data tidak ditemukan[]');
                }
                $id_citizen_enc_str=Crypt::encrypt($get_data->id_citizen);
                $data['nama']=$get_data->nama;
                $data['nip']=$get_data->nip;
                $data['no_hp']=$get_data->no_hp;
                $data['email']=$get_data->email;
                $data['token']=Hashids::encode($get_data->id_citizen)."-".$id_citizen_enc_str;
                $data['jabatan']=$get_data->jabatan;
                $data['pangkat']=$get_data->pangkat;
                $data['bagian']=$get_data->bagian;
                $data['pendidikan']=$get_data->id_pendidikan." ".$get_data->tempat_pendidikan;
                $data['riwayat_jabatan']=$get_data->riwayat_jabatan;
                $data['penghargaan']=$get_data->penghargaan;
                $data['foto']=$get_data->foto;
                $data['nik']=$get_data->nik;
                $data['tempat_lahir']=$get_data->tempat_lahir;
                $data['tgl_lulus']=$get_data->tgl_lulus;
                $data['masuk_kerja']=$get_data->masuk_kerja;
                

                $status=true;
                $msg="Data available";

            }catch(\Exception $e){
                $msg=$e->getMessage();
            }

            return ['status'=>$status, 'msg'=>$msg, 'data'=>$data];
        }
    }

?>