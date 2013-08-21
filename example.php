<?php
require 'config.php';
require 'app/folio_producer.php';

if (!isset($config)) { user_error('Missing configuration.'); }
$api = new DPSFolioProducer\FolioProducerAPI($config);


require 'app/services/session.php';
$session = new SessionService($config);
$response = $session->create();
var_dump($response);
echo '<hr />';
$session->get($response->ticket, $response->server);
echo '<hr />';
$session->delete($response->ticket, $response->server);
