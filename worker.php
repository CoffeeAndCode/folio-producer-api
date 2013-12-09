<?php
require 'vendor/autoload.php';

# You can call the worker like this:
# php /path/to/worker.php command_name '{ config_json}' '{ options_json }' >> /path/to/log/file &

$command = null;
$config = null;
$jobID = null;
$options = null;

if (isset($argv) && count($argv) >= 4) {
    $jobID = intval($argv[1]);
    $command = $argv[2];
    $config = $argv[3];
    if (count($argv) >= 5) {
        $options = $argv[4];
    }
} else {
    throw new Exception('Command takes four arguments: job_id, command_name, config_json, [options_json]');
    exit;
}

$config = json_decode($config, true);
if ($config === null && json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception('Config parse error: '.json_last_error());
    exit;
}

if (!is_null($options)) {
    $options = json_decode($options);
    if ($options === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Options parse error: '.json_last_error());
        exit;
    }
}

$config_backup = $config;
unset($config['db_host']);
$client = new DPSFolioProducer\Client($config);
$request = $client->execute($command, $options);

$dsn = 'mysql:dbname='.$config_backup['db_name'].';host='.$config_backup['db_host'];
$user = $config_backup['db_username'];
$password = $config_backup['db_password'];

$dbh = new \PDO($dsn, $user, $password);
$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$statement = $dbh->prepare('UPDATE `dpsfp-jobs` SET result = :result, finished_at = NOW() WHERE id = :job_id');
$statement->execute(array(
    ':job_id' => $jobID,
    ':result' => serialize($request)
));
