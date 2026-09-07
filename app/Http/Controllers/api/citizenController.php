<?php
    namespace App\Http\Controllers\api;

    use App\Http\Controllers\Controller;
    use App\Services\citizenService;
    use Illuminate\Contracts\Encryption\DecryptException;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Crypt;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Validation\ValidationException;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use Vinkla\Hashids\Facades\Hashids;

    class citizenController extends Controller
    {
        protected $citizenService;
        public function __construct(citizenService $citizen_service)
        {
            // throw new \Exception('Not implemented');
            $this->citizenService=$citizen_service;
        }
        /**
         * Get Profile
         *
         * @authenticated
         */
        public function profile(){
            // return auth()->user()
            return JWTAuth::parseToken()->authenticate();
        }

        /**
         * List Citizen
         * @group Admin - Citizen
         * @authenticated
         * @urlParam page integer required Nomor halaman. Example: 1
         */

        public function listCitizen($page){
            if($page < 1 || !is_numeric($page)){
                $page = 1;
            }
            $get_data=$this->citizenService->getCitizenPage($page);
            $status=$get_data['status'];
            $msg=$get_data['msg'];
            $jumlah=$get_data['jumlah'];
            $jumlah_halaman=$get_data['jumlah_halaman'];
            $data=$get_data['data'];

            return response()->json(['status'=>$status, 'msg'=>$msg, 'jumlah'=>$jumlah, 'jumlah_halaman'=>$jumlah_halaman, 'data'=>$data]);
        }

        /**
         * Detil Citizen
         * @group Admin - Citizen
         * @urlParam slug string required slug diambil dari hasil listcitizen. Example: xxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
         * @authenticated
         */

        public function getDetilCitizen($slug){
            $status=false;
            $data=null;
            $explode_slug = explode('-', $slug);
            $jlh=count($explode_slug);
            if($jlh > 0){
                $id_citizen=$explode_slug[0];
                $slug_str = str_replace($id_citizen."-", "", $slug);
                $get_data=$this->citizenService->getDetilCitizenAdmin($slug_str, $id_citizen);
                $status=$get_data['status'];
                $msg=$get_data['msg'];
                $data=$get_data['data'];
            }else{
                $msg="Data tidak valid";
            }

            return response()->json(['status'=>$status, 'msg'=>$msg, 'data'=>$data]);
        }

        /**
         * Update Citizen
         * @group Admin - Citizen
         * @authenticated
         */
        public function updateCitizen(Request $request){
            $config = config('costum.citizen');
            $validator = Validator::make($request->all(), [
                'riwayat_jabatan'=>['required', 'string'],
                'penghargaan'=>['required', 'string'],
                'foto' => ['nullable', 'image', 'mimes:'.implode(',', $config['foto']['mimes']), 'max:'.$config['foto']['max_size']],
                'token'=> ['required', 'string']
            ]);
            
            if($validator->fails()){
                return response()->json([
                    'status'=>false,
                    'msg'=>$validator->errors()->first()
                ], 422);
            }

            $exp_token_citizen=explode("-", $request->token);
            $jumlah = count($exp_token_citizen);
            if($jumlah !== 2){
                return response()->json(['status'=>false, 'msg'=>'Data token tidak valid'], 400);
            }

            try{
                $citizen_id_dec=Hashids::decode($exp_token_citizen[0]);
                $citizen_id_dec_string=Crypt::decrypt($exp_token_citizen[1]);
                if(empty($citizen_id_dec)){
                    return response()->json(['status'=>false, 'msg'=>'Invalid token (Data tidak ditemukan)'], 400);
                }

                if((int)$citizen_id_dec[0] !== (int)$citizen_id_dec_string){
                    return response()->json(['status'=>false, 'msg'=>'Invalid token (Data tidak ditemukan)'], 400);
                }
            }catch(DecryptException $e){
                return response()->json(['status'=>false, 'msg'=>'Invalid token'], 400);
            }

            $filename = null;
            if($request->hasFile('foto')){
                $file = $request->file('foto');
                $filename=uniqid('foto_profile_').".".$file->getClientOriginalExtension();
                $dst = "image/foto_pegawai";
                $upload = $file->storeAs($dst, $filename, 'public');
                if(!$upload){
                    return response()->json(['status'=>false, 'msg'=>'File foto tidak dapat diupload', 'err'=>$filename], 500);
                }
            }
            
            $update = $this->citizenService->updateCitizen($citizen_id_dec[0], $filename, $request->riwayat, $request->penghargaan);
            
            return response()->json(['status'=>$update['status'], 'msg'=>$update['msg']], $update['code']);
        }
    }
?>