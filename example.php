<?php
require 'config.php';
require 'app/folio_producer.php';

if (!isset($config)) { user_error('Missing configuration.'); }
$api = new DPSFolioProducer\FolioProducerAPI($config);


require 'app/services/session.php';
$session = new SessionService($config);
$session->delete('4151E85E4054F7A4BB7BFAF9CC3FB5385EA6D');
