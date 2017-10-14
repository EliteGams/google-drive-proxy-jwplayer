<?php
$expire = time() + 3600; //default expires in 1hr
$dt = base64_decode($expire);
 
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'PWaanA*()!#EGyKaaZ'; //letter only
		$secret_iv = 'PWAsrqWUN*()!#RETyAAga';
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
?>
