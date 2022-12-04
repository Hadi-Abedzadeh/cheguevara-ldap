<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class LdapFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ldapfetch';

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
		
		// const LDAP_AUTH_SERVER     = 'http://10.131.3.96:3000/';
		// const LDAP_FETCH_ACCOUNTS  = 'http://10.131.3.96:3000/fetch';
	
        $ldapUsers = json_decode(Http::get(Controller::LDAP_FETCH_ACCOUNTS)->body());

        DB::table('temp_users')->truncate();
        foreach($ldapUsers as $ldapUser)
            DB::insert("INSERT INTO temp_users (username, created_at, updated_at) VALUES ('$ldapUser', GETDATE(), GETDATE())");

        $users = DB::select("SELECT tu.* FROM temp_users tu LEFT JOIN users u ON (tu.username = u.username) WHERE u.username IS NULL");

        if(isset($users)){
            foreach ($users as $user) {
                $username = strtolower($user->username);
                $userModel = new User();
                $userModel->username = $username;
                $userModel->password = Hash::make(rand(0,50));
                $userModel->name = $username;
                $userModel->ldap_user = 'gig\\' . $username;
                $userModel->email = $username . Controller::POSTFIX_MAIL;
                $userModel->assignRole('کاربر عادی');
                $userModel->save();
            }
        }
    }
}
