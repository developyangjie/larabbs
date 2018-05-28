<?php

namespace App\Console\Commands;

use App\Models\User;
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

        $userId = $this->ask("what is id");

        $user = User::find($userId);

        if(!$user){
            $this->error("params error");
        }

        if($name == 'F'){
            $user->assignRole("Founder");
        }else{
            $user->assignRole("Maintainer");
        }
    }
}
