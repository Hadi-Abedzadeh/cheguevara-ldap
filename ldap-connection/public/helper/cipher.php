<?php
	function cipher()
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
	
	function cipher_encode($text, $data)
	{
		$arrText = str_split($text);
		foreach ($arrText as $tx) {
			if(in_array($tx, $data)) {
				$arrKey[] 	= array_search($tx, $data);
			} 
		}
		return join($arrKey);
	}
	
	function cipher_decode($encode, $data)
	{
		$arrKey = str_split($encode, 5);
		foreach ($arrKey as $key) {
			$result[] = $data[$key];
		}
		return join($result);
	}