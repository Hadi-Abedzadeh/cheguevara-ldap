<?php
require_once('cipher.php');

function getSAMaccounts($query){
	
	$serverName = "10.131.0.96";
    $connectionInfo = array( "Database"=>"ldap", "UID"=>"hadi", "PWD"=>"123456");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);

	if(!$conn ) {
		die( print_r( sqlsrv_errors(), true));            
	}
	
    $stmt = sqlsrv_query($conn, $query);
  
	while($row = sqlsrv_fetch_array($stmt)) $data[] = $row[0];
	
    sqlsrv_close($conn);
	
	return $data;
}