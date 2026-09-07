<?php
    namespace App\Services;

    class waService{
        protected $waUrl;
        protected $token;
        protected $key;
        
        public function __construct(){
            // throw new \Exception('Not implemented
            $this->waUrl = config('services.wa_env.url');
            $this->token = config('services.wa_env.token');
            $this->key = config('services.wa_env.key');
        }

        public function sendWa($msg, $reciver, $type){
            $curl = curl_init();

            // Prepare message data for multiple recipients

            if($type === "text"){
                $full_endpoint = $this->waUrl."/send-message";
            }else{
                return ['status'=>false, 'msg'=>'Unsupport message type'];
            }

            $msg_title = "*Pesan Otomatis:*\n\n";
            $newline="\n\n";
            $pesan_jawab="_Mohon untuk menjawab pesan ini dengan *Ya* agar kami dapat selalu mengirimkan pesan._";
            $footer = "```Pengadilan Tinggi Bengkulu```";
            $data = [
                'phone'=> $reciver,
                'message' => $msg_title."".$msg."".$newline."".$pesan_jawab."".$newline."".$footer,
                'flag'=>'instant'
            ];

            // Set up the API request headers
            curl_setopt($curl, CURLOPT_HTTPHEADER,
                array(
                    "Authorization: $this->token.$this->key",
                )
            );
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_URL,  $full_endpoint);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);
            curl_close($curl);
            $decode = json_decode($result);
            $status = $decode->status;
            $msg = $decode->message;

            return ['status'=>$status, 'msg'=>$msg];

        }
    }

?>