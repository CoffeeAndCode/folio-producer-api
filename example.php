<?php
require 'config.php';
require 'app/client.php';

if (!isset($config)) { user_error('Missing configuration.'); }
$client = new DPSFolioProducer\Client($config);
$request = $client->create_session();
print_r($request->get_response_code());
