<?php
require 'config.php';
require 'app/folio_producer.php';

if (!isset($config)) { user_error('Missing configuration.'); }
$api = new DPSFolioProducer\FolioProducerAPI($config);


require 'app/services/session.php';
$session = new SessionService($config);
$session->create();
