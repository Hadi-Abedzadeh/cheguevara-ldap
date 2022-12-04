<?php

require 'flight/Flight.php';
require 'helper/functions.php';

Flight::route('/', function () {

	$username = @$_REQUEST['username'];
	$password = @$_REQUEST['password'];
	
	if(!isset($username) OR !isset($password)){
		return Flight::json(json_encode(['credential' => 'username or password is not sent']), $code = 401, $encode = false, $charset = 'utf-8');
	}
	
	$data = cipher();

	echo $decode_username = cipher_decode($username, $data);
	echo $decode_password = cipher_decode($password, $data);
		
	ob_start();
	system("ldap\LDAP.exe -auth {$decode_username} {$decode_password}");
	$ldap = ob_get_contents();
	ob_end_clean();		

	return Flight::json($ldap, $code = 200, $encode = false, $charset = 'utf-8');
});


Flight::route('/fetch', function () {
	$data = getSAMaccounts("SELECT samaccountname FROM ldap_users");
	return Flight::json(json_encode($data), $code = 200, $encode = false, $charset = 'utf-8');
});

Flight::start();