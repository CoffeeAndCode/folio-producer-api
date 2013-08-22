<?php
require 'config.php';
require 'app/client.php';

if (!isset($config)) { user_error('Missing configuration.'); }
$client = new DPSFolioProducer\Client($config);
