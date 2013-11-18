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
     * Class constructor.
     *
     * @param array $config The configuration hash to startup the library.
     */
    public function __construct($config)
    {
        $this->config = new Config($config);
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
        $command_class = $this->_getCommandClass($command_name);
        $command = new $command_class($this->config, $options);

        // shortcut API request if Command is invalid
        if ($command->isValid()) {
            if ($command_name !== 'create_session' && !isset($this->config->ticket)) {
                $this->execute('create_session');
            }

            $request = $command->execute();

            // if an InvalidTicket response is returned, reauthenticate and retry
            if ($request && in_array('API returned status InvalidTicket', $request->errors()) &&
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

        return (object) array('response' => (object) array(
                'errors' => $command->errors,
                'status' => 'ValidationError'
            )
        );
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
