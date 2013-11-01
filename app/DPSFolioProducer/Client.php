<?php
/**
 * Adobe DPS API client library.
 *
 * @category  AdobeDPS
 * @package   DPSFolioProducer
 * @author    Jonathan Knapp <jon@coffeeandcode.com>
 * @copyright 2013 Jonathan Knapp
 * @license   MIT https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE
 * @version   1.0.0
 * @link      https://github.com/CoffeeAndCode/folio-producer-api
 */
namespace DPSFolioProducer;

/**
 * Client class that handles communication with the DPS API.
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @license  MIT https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class Client
{
    /**
     * The class configuration object.
     * @var Config
     */
    protected $config = null;

    /**
     * The Folio service for API calls.
     * @var Folio
     */
    protected $folio = null;

    /**
     * THe Session service for API calls.
     * @var Session
     */
    protected $session = null;

    /**
     * Class constructor.
     *
     * @param array $config The configuration hash to startup the library.
     */
    public function __construct($config)
    {
        $this->config = new Config($config);
        $this->folio = new Services\FolioService($this->config);
        $this->session = new Services\SessionService($this->config);
        $this->_syncSession();
    }

    /**
     * Creates a new session through Adobe's DPS API.
     *
     * @return Request|null Returns a request object if the call is made, null otherwise.
     */
    public function createSession()
    {
        $request = null;
        if (!isset($this->config->ticket) || !$this->config->ticket) {
            $request = $this->session->create();
            $this->config->download_server = $request->response->downloadServer;
            $this->config->download_ticket = $request->response->downloadTicket;
            $this->config->request_server = $request->response->server;
            $this->config->ticket = $request->response->ticket;

            if (session_id()) {
                $_SESSION['download_server'] = $this->config->download_server;
                $_SESSION['download_ticket'] = $this->config->download_ticket;
                $_SESSION['request_server'] = $this->config->request_server;
                $_SESSION['ticket'] = $this->config->ticket;
            }
        }

        return $request;
    }

    /**
     * Execute an API method utilizing the passed options.
     *
     * @param string $command_name The name of the API command to make in snakecase.
     * @param array  $options      The configuration options to use for the API request.
     *
     * @return Request|null Returns a request object if the call is made, null otherwise.
     */
    public function execute($command_name, $options=array())
    {
        $command_class = $this->_getCommandClass($command_name);
        $command = new $command_class($this->config, $options);

        if (!isset($this->config->ticket)) {
            $this->createSession();
        }

        $command->folio = $this->folio;
        $command->session = $this->session;
        $request = $command->execute();

        // if an InvalidTicket response is returned, reauthenticate and retry
        if ($request->get_response_code() === 200
            && property_exists($request->response, 'status')
            && $request->response->status === 'InvalidTicket'
            && !$command->is_retry
        ) {
            $this->_reset();
            $this->createSession();
            $request = $command->retry();
        }

        return $request;
    }

    /**
     * Converts a snakecase string to camelcase.
     *
     * @param string $word The string to convert from snake to camelcase.
     *
     * @return string
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
    private function _getCommandClass($command_name)
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
        $this->config->request_server = null;
        $this->config->ticket = null;

        if (session_id()) {
            session_unset();
        }
    }

    /**
     * Sets class variables based on session variables if they are set.
     *
     * @return null
     */
    private function _syncSession()
    {
        if (session_id()) {
            if (isset($_SESSION) && isset($_SESSION['download_server'])) {
                $this->config->download_server = $_SESSION['download_server'];
            }

            if (isset($_SESSION) && isset($_SESSION['download_ticket'])) {
                $this->config->download_ticket = $_SESSION['download_ticket'];
            }

            if (isset($_SESSION) && isset($_SESSION['request_server'])) {
                $this->config->request_server = $_SESSION['request_server'];
            }

            if (isset($_SESSION) && isset($_SESSION['ticket'])) {
                $this->config->ticket = $_SESSION['ticket'];
            }
        }
    }
}
