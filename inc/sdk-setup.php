<?php

require_once("netatmo_api/NAApiClient.php");

$config = array();
// these API keys are property of NetAtmo licensed to Robert Nucci, you CAN'T use them for your apps.
// If you are thinking to develop something, get your API Keys here: https://dev.netatmo.com
$config['client_id'] = '5561f9824a5a88f744c75c3f';
$config['client_secret'] = 'bXeRsYsI0gvhh3BNbjioEI7hBF6rwT1ld6ue18OZ';

$config['scope'] = 'read_station';
$client = new NAApiClient($config);

?>