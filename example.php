<?php
require 'config.php';
require 'app/client.php';

if (!isset($config)) { user_error('Missing configuration.'); }
$client = new DPSFolioProducer\Client($config);

// $response = $session->create();
// var_dump($response);
// echo '<hr />';
// $session->get($response->ticket, $response->server);
// echo '<hr />';
// $session->delete($response->ticket, $response->server);
