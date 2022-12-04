<?php
    public static function cipher()
    {
        // digit 5 for str_split by 5 char
        $rand = 1000;
        $i = $rand + 9989;
        foreach (range('A', 'Z') as $char) {
            $data[$i] =  $char;
            $i = $i + $rand;
            $rand++;
        }
        foreach (range('!', '@') as $char) {
            $data[$i] =  $char;
            $i = $i + $rand;
            $rand++;
        }
        foreach (range('a', 'z') as $char) {
            $data[$i] =  $char;
            $i = $i + $rand;
            $rand++;
        }

        $i++;
        $data[$i] = ' ';
        $i++;
        $data[$i] = '_';
        return $data;
    }

    public static function cipher_encode($text, $data)
    {
        $arrText = str_split($text);
        foreach ($arrText as $tx) {
            if(in_array($tx, $data)) {
                $arrKey[] 	= array_search($tx, $data);
            }
        }
        return join($arrKey);
    }

    public static function cipher_decode($encode, $data)
    {
        $arrKey = str_split($encode, 5);
        foreach ($arrKey as $key) {
            $result[] = $data[$key];
        }
        return join($result);
    }


    public static function cheGuevaraLdap($user, $pass)
    {
		
        $data = self::cipher();

	// const LDAP_AUTH_SERVER     = 'http://10.131.3.96:3000/';
    // const LDAP_FETCH_ACCOUNTS  = 'http://10.131.3.96:3000/fetch';
	
        $response = Http::get(Controller::LDAP_AUTH_SERVER, [
            'username' => self::cipher_encode($user, $data),
            'password' => self::cipher_encode($pass, $data),
        ]);

        $response = $response->body();

        $ldap = @json_decode($response);

        $i = 0;

        if($ldap->success == true){
            $ldapVar = $ldap->group[0];
            $ldapVar = json_decode($ldapVar);
            if(!is_null($ldapVar)){
                foreach($ldapVar as $var) {
                    if ($i >= 1) {
                        $ar['title']          = $var->title;
                        $ar['company']        = $var->company;
                        $ar['employeeNumber'] = $var->employeeNumber;
                    }
                    $ar['members'][] = $var->members;
                    $i++;
                }
            }else{
                return FALSE;
            }
        } else {
            return FALSE;
        }

        DB::update("UPDATE users SET password = :password WHERE username = :username", ['password' => Hash::make($pass), 'username' => $user]);
        return TRUE;
    }
