<?php
/**
 * DPSFolioProducer\Client class
 */
namespace DPSFolioProducer;

/**
 * An API client that automatically handles session creation and retry
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class Client
{
    /**
     * Determines wether API requests are made asynchronously or not.
     * @var boolean
     */
    protected $async = false;

    /**
     * The class configuration object.
     * @var Config
     */
    protected $config = null;

    /**
     * Class constructor.
     *
     * @param array $config The configuration hash to startup the library.
     */
    public function __construct($config)
    {
        $this->config = new Config($config);
        if (isset($this->config->db_host) &&
            isset($this->config->db_name) &&
            isset($this->config->db_password) &&
            isset($this->config->db_username)) {

            // connect to database and set it up if it doesn't exist
            $dsn = 'mysql:dbname='.$this->config->db_name.';host='.$this->config->db_host;
            $user = $this->config->db_username;
            $password = $this->config->db_password;

            try {
                $dbh = new \PDO($dsn, $user, $password);
                $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $result = $dbh->query('SHOW TABLES LIKE "dpsfp-jobs"');
                if ($result->rowCount() === 0) {
                    $result = $dbh->query(
                        'CREATE TABLE IF NOT EXISTS `dpsfp-jobs` (
                        `id` INT AUTO_INCREMENT NOT NULL,
                        `user_id` INT NOT NULL,
                        `command` TEXT NOT NULL,
                        `result` TEXT DEFAULT NULL,
                        `created_at` DATETIME NOT NULL,
                        `finished_at` TIMESTAMP DEFAULT 0,
                        `log` TEXT NOT NULL,
                        PRIMARY KEY (`id`))
                        CHARACTER SET utf8 COLLATE utf8_general_ci');
                }
                $this->async = true;
            } catch (\PDOException $e) {
                throw new \Exception('DB Connection failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Execute an API method utilizing the passed options.
     *
     * @param string $command_name The name of the API command to make in snakecase.
     * @param array  $options      The configuration options to use for the API request.
     *
     * @return HTTPRequest|null Returns a request object if the call is made, null otherwise.
     */
    public function execute($command_name, $options=array())
    {
        if ($this->async && !isset($argv)) {
            $log = tempnam(sys_get_temp_dir(), 'dpsfp-request-');
            $command = 'php '.escapeshellarg('worker.php').' '.escapeshellarg($command_name).' '.escapeshellarg($this->config->toJSON()).' '.escapeshellarg(json_encode($options)).' >> '.escapeshellarg($log).' &';

            $dsn = 'mysql:dbname='.$this->config->db_name.';host='.$this->config->db_host;
            $user = $this->config->db_username;
            $password = $this->config->db_password;

            $dbh = new \PDO($dsn, $user, $password);
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $statement = $dbh->prepare('INSERT INTO `dpsfp-jobs` (user_id, command, created_at, log) VALUES (:user_id, :command, NOW(), :log)');
            $statement->execute(array(
                ':user_id' => 10,
                ':command' => $command,
                ':log' => $log
            ));

            $jobID = $dbh->lastInsertId();
            // prepend jobID to the command
            $command = 'php '.escapeshellarg('worker.php').' '.$jobID.' '.substr($command, strlen('php '.escapeshellarg('worker.php')));
            $statement = $dbh->prepare('UPDATE `dpsfp-jobs` SET command = :command WHERE id = :job_id');
            $statement->execute(array(
                ':job_id' => $jobID,
                ':command' => $command
            ));
            exec($command);

            return $jobID;
        }

        $command_class = $this->_getCommandClass($command_name);
        $command = new $command_class($this->config, $options);

        // shortcut API request if Command is invalid
        if ($command->isValid()) {
            if ($command_name !== 'create_session' && !isset($this->config->ticket)) {
                $this->execute('create_session');
            }

            $request = $command->execute();

            // if an InvalidTicket response is returned, reauthenticate and retry
            if ($request &&
                $this->isInvalidTicketError($request->errors()) &&
                !$command->is_retry
            ) {
                $this->_reset();
                $this->execute('create_session');
                $request = $command->retry();
            }

            // 10 min before tickets expire, new ones are sent with every
            // API call that returns json
            if ($request &&
                property_exists($request, 'response') &&
                is_object($request->response)) {
                if (property_exists($request->response, 'ticket')) {
                    $this->config->ticket = $request->response->ticket;
                }

                if (property_exists($request->response, 'downloadTicket')) {
                    $this->config->download_ticket = $request->response->downloadTicket;
                }
            }
            return $request;
        }

        return new ErrorResponse($command->errors);
    }

    /**
     * Check if an InvalidError was found in the passed errors array
     *
     * @param  array  $errors Array of Error objects encountered
     * @return boolean Return true if InvalidError was found, false otherwise
     */
    private function isInvalidTicketError($errors)
    {
        foreach ($errors as $error) {
            if (is_a($error, '\DPSFolioProducer\Errors\APIResponseError') &&
                $error->status === 'InvalidTicket'
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Converts a snakecase string to camelcase.
     *
     * @param string $word The string to convert from snake to camelcase.
     *
     * @return string Return the camelized text
     */
    private function _camelize($word)
    {
        $words = explode('_', $word);
        $words = array_map(function ($word) {
            if (in_array($word, array('api', 'html'))) {
                return strtoupper($word);
            }
            return ucfirst($word);
        }, $words);

        return implode('', $words);
    }

    /**
     * Return the full class name of the requested Command.
     *
     * @param string $command_name The requested Command name.
     *
     * @return string Return the full class name.
     */
    public function _getCommandClass($command_name)
    {
        return '\\DPSFolioProducer\\Commands\\'.$this->_camelize($command_name);
    }

    /**
     * Reset the object's properties as if it was not used.
     *
     * @return void
     */
    private function _reset()
    {
        $this->config->reset();
    }
}
