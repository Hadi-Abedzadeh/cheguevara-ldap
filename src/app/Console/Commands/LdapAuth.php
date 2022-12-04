<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LdapAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ldap {user} {pass}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $user = $this->argument('user');
        $pass = $this->argument('pass');

       // $output=(system(public_path("ldap\ldap.exe -auth {$user} {$pass}")));
        $output = system(public_path("ldap\ldap.exe -auth {$user} {$pass}"));
       // print_r($output);
//        $output=implode('',$output);
        response()->json($output);


    }
}
