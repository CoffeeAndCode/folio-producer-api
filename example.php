<?php
require 'config.php';
require 'app/client.php';

if (!isset($config)) { user_error('Missing configuration.'); }
$client = new DPSFolioProducer\Client($config);
$client->create_session();
$request = $client->get_folio_metadata();

echo '<h1>Folios</h1>';
foreach ($request->response->folios as $folio) {
    print_r($folio);
    echo '<hr />';
}
