<?php
namespace DPSFolioProducer\Services;

class Service {
    protected $ticket = null;

    protected function auth_header() {
        return 'Authorization: AdobeAuth ticket="'.urlencode($this->config->ticket).'"';
    }

    protected function create_url($suffix='') {
        $server = $this->config->api_server;
        if (isset($this->config->request_server)) {
            $server = $this->config->request_server;
        }
        return $server.'/webservices/'.$suffix;
    }
}
