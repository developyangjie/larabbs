<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FounderUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:founder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set user founder';

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
        $name = $this->anticipate('What is your set,Founder or Maintainer?', ['F', 'M']);

        $userId = $this->ask("输入用户 id");

        $user = User::find($userId);

        if(!$user){
            $this->error("参数错误");
        }

        if($name == 'F'){
            $user->assignRole("Founder");
        }else{
            $user->assignRole("Maintainer");
        }
    }
}
