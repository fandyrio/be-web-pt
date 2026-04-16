<?php

namespace App\Console\Commands;

use App\Models\Citizen;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class generateSU extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-su {nip}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Super User Website PT Bengkulu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nip=$this->argument('nip');
        $check_nip=Citizen::where("nip", $nip)->first();
        if(!is_null($check_nip)){
            $citizen_id=$check_nip['id'];
            $get_users=User::where('citizen_id', $citizen_id)->exists();
            if(!$get_users){
                $user=new User;
                $user->citizen_id=$citizen_id;
                $user->password=Hash::make($nip);
                $user->role_code='r-001';
                $user->save();
            }else{
                $this->info('User sudah ada');    
            }
        }else{
            $this->info('Data tidak ditemukan');
        }
    }
}
