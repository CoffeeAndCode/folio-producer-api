<?php
namespace DPSFolioProducer;

class Request {
    protected $is_retry = false;
    public $options = null;
    public $response_headers = array();
    public $response = null;
    public $url = null;

    public function __construct($url, $options) {
        $this->options = $options;
        $this->url = $url;
    }

    public function run() {
        $context = stream_context_create($this->options);
        $response = file_get_contents($this->url, false, $context);

        $this->response_headers = $http_response_header;
        $this->response = json_decode($response);
        if ($this->response === null) {
            if (json_last_error() !== JSON_ERROR_NONE) {
                user_error(json_last_error());
            }
        }

        if ($this->get_response_code() === 200 &&
            property_exists($this->response, 'status') &&
            $this->response->status === 'InvalidTicket' &&
            !$this->is_retry) {

            // make create_session call
                // on success, make original call
                // $this->retry();
                // on failure, fail method
                // user_error('create_session call failed, unable to retry');
        }
        return $this->response;
    }

    public function get_response_code() {
        $response_code = null;
        if ($this->response_headers && count($this->response_headers)) {
            preg_match('/^(([a-zA-Z]+)\/([\d\.]+))\s([\d\.]+)\s(.*)$/', $this->response_headers[0], $matches);
            if ($matches) {
                $response_code = intval($matches[4]);
            }
        }
        return $response_code;
    }

    public function retry() {
        $this->response_headers = null;
        $this->response = null;
        $this->is_retry = true;
        $this->run();
    }
}
