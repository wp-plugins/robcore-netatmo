<?php
/*
 *	OTTENGO TOKEN UTENTE DA SUO USER E PASS
 */
if (get_option("robcore_nap_account")) {
	$array_account_da_DB = get_option("robcore_nap_account");

	$username_sdk = $array_account_da_DB[0];
	$password_sdk = $array_account_da_DB[1];

	$client->setVariable("username", $username_sdk);
	$client->setVariable("password", $password_sdk);
	try
	{
	    $tokens = $client->getAccessToken();        
	    $refresh_token = $tokens["refresh_token"];
	    $access_token = $tokens["access_token"];
	}
	catch(NAClientException $ex)
	{
	    echo "<span style='font-weight: bold;'>An error happend while trying to retrieve your tokens, try to login again.</span><br>";
	}
}?>