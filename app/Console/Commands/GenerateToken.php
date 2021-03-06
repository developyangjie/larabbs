<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:generate_token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->ask("输入用户 id");

        $user = User::find($userId);

        if(!$user){
            $this->error("参数错误");
        }

        $ttl = 365*24*60;

        $this->info(\Auth::guard("api")->setTTL($ttl)->login($user));


    }
}
