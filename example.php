<?php
session_start();

require_once 'app/config.php';
require_once 'app/client.php';
include 'config.php';

if (!isset($config)) { user_error('Missing configuration.'); }
$client = new DPSFolioProducer\Client($config);
$client->create_session();

$request = $client->get_folio_metadata();

echo '<h1>Folios</h1>';
foreach ($request->response->folios as $folio) {
    print_r($folio);
    echo '<hr />';
}

$options = array(
    'folioName' => 'Folio Name',
    'folioNumber' => 'folio-'.time(),
    'magazineTitle' => 'Magazine Title',
    'resolutionHeight' => 240,
    'resolutionWidth' => 240
);
$request = $client->create_folio($options);
var_dump($request);
