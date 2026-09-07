<?php

namespace App\Console\Commands;

use App\Models\Apps_token;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class generateWaKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-wa-key {apps_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate WA API Key for register Application.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apps_name = $this->argument('apps_name');
        $get_name = Apps_token::where('name', $apps_name)->exists();
        if(!$get_name){
            $api_key = 'wa_live_pt' . bin2hex(random_bytes(32));
            $new_key = new Apps_token;
            $new_key->name = $this->argument('apps_name');
            $new_key->hashed_key = Hash::make($api_key);
            $new_key->is_active = true;
            $new_key->save();
            $this->info($api_key);
        }else{
            $this->info("Your application name already registerd");
        }
    }
}
