<?php
namespace DPSFolioProducer;

class Service {
    protected $api_server = null;

    protected function create_url($suffix='') {
        $server = $this->config['api_server'];
        if ($this->api_server) {
            $server = $this->api_server;
        }
        return $server.'/webservices/'.$suffix;
    }
}
