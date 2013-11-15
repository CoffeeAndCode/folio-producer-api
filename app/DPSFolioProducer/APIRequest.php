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
class APIRequest
{
    private $settings;
    private $url;

    public function __construct($url, $config, $settings)
    {
        $this->config = $config;
        $this->settings = $settings;
        $this->url = $this->url($url);

        // update url depending on urlType if set
        if (isset($settings['urlType']) &&
            $settings['urlType'] === 'download') {
            $this->url = $this->downloadURL($url);
        }
    }

    public function run() {
        $options = array(
            'http' => array(
                'header'  => $this->getHeaders(),
                'method'  => strtoupper($this->settings['type']),
                //'proxy' => 'tcp://localhost:8888',
                'protocol_version' => 1.1
            )
        );

        if (isset($this->settings['data'])) {
            $options['http']['content'] = $this->settings['data'];
        }

        $request = new Services\Request($this->url, $options);
        if (isset($this->settings['file'])) {
            $request->run($this->settings['file']);
        } else {
            $request->run();
        }
        return $request;
    }

    private function authHeader()
    {
        if (isset($this->settings['urlType']) &&
            $this->settings['urlType'] === 'download') {
            return 'AdobeAuth ticket="'.urlencode($this->config->download_ticket).'"';
        }
        return 'AdobeAuth ticket="'.urlencode($this->config->ticket).'"';
    }

    private function downloadURL($path) {
        $server = $this->config->download_server;
        return $server.'/webservices/'.$path;
    }

    private function getHeaders() {
        $headers = array();
        $headerHash = array();

        // use custom headers if specified
        if (isset($this->settings['headers'])) {
            $headerHash = $this->settings['headers'];
        }

        // set Content-Length if 'data' is present
        if (isset($this->settings['data'])) {
            $headerHash['Content-Length'] = strlen($this->settings['data']);
        }

        if (!isset($this->settings['dataType']) ||
            $this->settings['dataType'] === 'json') {
            $headerHash['Content-Type'] = 'application/json; charset=utf-8';
        }

        $headerHash['Authorization'] = $this->authHeader();

        // collapse array keys with differing case
        $headerHash = array_change_key_case($headerHash);

        // return array of header strings
        foreach (array_keys($headerHash) as $header) {
            $headers[] = ucwords($header).': '.$headerHash[$header];
        }
        return $headers;
    }

    private function url($path) {
        $server = $this->config->api_server;
        if (isset($this->config->request_server) && $this->config->request_server) {
            $server = $this->config->request_server;
        }

        return $server.'/webservices/'.$path;
    }
}
