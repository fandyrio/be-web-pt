<?php
    namespace App\Http\Controllers\api;

    use App\Http\Controllers\Controller;
    use App\Services\citizenService;
    use Illuminate\Http\Request;
    use Illuminate\Validation\ValidationException;
    use Tymon\JWTAuth\Facades\JWTAuth;

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
            $status = false;
            try{
                $config = config('costum.citizen');
                $request->validate([
                    'riwayat_jabatan'=>['required', 'string'],
                    'penghargaan'=>['required', 'string'],
                    'foto' => ['nullable', 'image', 'mimes:'.implode(',', $config['foto']['mimes']), 'max:'.$config['foto']['max_size']]
                ]);
                if($request->hasFile('foto')){
                    $file = $request->file('foto');
                    $filename = uniqid('foto_profile').".".$file->getClientOriginalExtension();

                }
            }catch(ValidationException $e){
                $msg=$e->validator->errors()->first();
            }
        }
    }
?>